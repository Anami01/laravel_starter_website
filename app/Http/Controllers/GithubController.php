<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class GithubController extends Controller
{

  private $client;

  /*
   * Github username
   *
   * @var string
   * */
  private $username;

  public function __construct(\Github\Client $client)
  {
    $this->client = $client;
    $this->username = env('GITHUB_USERNAME');
  }
  
  public function index()
  {
    try {
      $repos = $this->client->api('current_user')->repositories();
	  
	  return view('repos', ['repos' => $repos]);
    } catch (\RuntimeException $e) {
      $this->handleAPIException($e);
    }
  }

  public function finder()
{
  $repo = Input::get('repo');
  $path = Input::get('path', '.');

  try {
    $result = $this->client->api('repo')->contents()->show($this->username, $repo, $path);

    return view('finder', ['parent' => dirname($path), 'repo' => $repo, 'items' => $result]);
  } catch (\RuntimeException $e) {
    $this->handleAPIException($e);
  }
}

public function edit()
{
  $repo = Input::get('repo');
  $path = Input::get('path');

  try {
    $file = $this->client->api('repo')->contents()->show($this->username, $repo, $path);

    $content = base64_decode($file['content']);
    $commitMessage = "Updated file " . $file['name'];

    return View::make('file_update', [
        'file'    => $file,
        'path'    => $path,
        'repo'    => $repo,
        'content' => $content,
        'commitMessage'  => $commitMessage
    ]);
  } catch (\RuntimeException $e) {
    $this->handleAPIException($e);
  }
}

public function update()
{
  $repo = Input::get('repo');
  $path = Input::get('path');
  $content = Input::get('content');
  $commit = Input::get('commit');

  try {
    $oldFile = $this->client->api('repo')->contents()->show($this->username, $repo, $path);
    $result = $this->client->api('repo')->contents()->update(
        $this->username,
        $repo,
        $path,
        $content,
        $commit,
        $oldFile['sha']
    );

    return \Redirect::route('commits', ['path' => $path, 'repo' => $repo]);
  } catch (\RuntimeException $e) {
    $this->handleAPIException($e);
  }
}
public function commits($repo,$path)
{
  try {
    $commits = $this->client->api('repo')->commits()->all($this->username, $repo, ['path' => '/']);
    return view('commits', ['commits' => $commits]);
  } catch (\RuntimeException $e) {
    $this->handleAPIException($e);
  }
}

public function add_commit(){
	$commitData = ['message' => 'Upgrading documentation', 'tree' => 'master', 'parents' => ['master']];
	$commit = $this->client->api('gitData')->commits()->create($this->username, 'lara_with_git', $commitData);
	echo $commit;
}

public function handleAPIException($e)
{
  dd($e->getCode() . ' - ' . $e->getMessage());
}
}
