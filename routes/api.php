<?php

use App\Jobs\EnvoyDeployJob;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api', 'as' => 'api.'], function () {
});

Route::get('/servers/{server}/connect', function (Server $server) {
    $key = Storage::get('keys/' . $server->id . '.pub');

    echo '#!/bin/bash' . PHP_EOL
        . "echo \"Adding $server->name key for user \$(whoami)\"" . PHP_EOL
        . "echo \"$key\" >> ~/.ssh/authorized_keys" . PHP_EOL
        . 'echo "Key added!"';

    exit;
})->name('api.servers.connect');

Route::post('/projects/{project}/deploy', function (Request $request, Project $project) {
    abort_if(! $project->push_to_deploy, 500, ['error' => 'PTD is not enabled on this project.']);

    if ($request->header('x-github-event') == 'push') {
        // Something have been pushed!
        [, $foo, $bar] = explode('/', $request->ref);

        if ($foo == 'heads') {
            // That's a commit on a $bar branch
            if ($bar == $project->branch) {
                // Trigger deployment

                [$user, $repo] = explode('/', $project->repository);
                $gh_client = $project->user->github()->repository();
                $gh_branch = rescue(fn () => $gh_client->branches($user, $repo, $project->branch));

                if (! $gh_branch) {
                    return abort(500, ['error', 'It seems that we are unable to access the configured repository.']);
                }

                $commit = array_merge(
                    [
                        'from_branch' => $gh_branch['name'],
                        'from_repository' => $project->repository,
                    ],
                    $gh_branch['commit'],
                );

                $deployment = $project->deployments()->create([
                    'server_id' => $project->server->id,
                    'status' => 'queued',
                    'release' => date('YmdHis'),
                    'commit' => $commit,
                ]);

                dispatch(new EnvoyDeployJob($deployment));
            }
        }

        if ($foo == 'tags') {
            // That's the $bar tag. We ignore that for the moment.
            // TODO
        }
    }

    if ($request->header('x-github-event') == 'release') {
        // A relase has been $request->action (prereleased, released, deleted, ...)
        if ($request->action == 'released') {
            // TODO
        }
    }

    return response()->json([], 200);
})->name('api.projects.deploy');
