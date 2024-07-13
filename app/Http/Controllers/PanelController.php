<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PanelController extends Controller
{
  public function index(){
    $totalMovies = Movie::where('type', 'movie')->count();
    $totalTVShows = Movie::where('type', 'tv_show')->count();
    $totalViews = Movie::sum('views');
    $user = User::where('id', auth()->user()->id)->first();

    return view('panel.dashboard', [
      'totalMovies' => $totalMovies,
      'totalTVShows' => $totalTVShows,
      'totalViews' => $totalViews,
      'user' => $user,
    ]);
  }

  public function panel_movies(){
    $movies = Movie::where('type', 'movie')->orderByDesc('updated_at')->paginate(10);
    return view('panel.movies.index', [
      'movies' => $movies
    ]);
  }

  public function movies_form(){
    return view('panel.movies.form');
  }

  public function movies_create(Request $request){
    // Validate data
    $validatedData = $request->validate([
      'title' => ['required', 'string', 'max:255'],
      'overview' => ['required', 'string'],
      'release_date' => ['required', 'string'],
      'vote_average' => ['required', 'string'],
      'runtime' => ['required', 'string'],
      'youtube_id' => ['required', 'string'],
      'type' => ['in:movie,tv_show']
    ]);

    // Check is file valid
    if ($request->file('backdrop_file')->isValid() && $request->file('poster_file')->isValid()){
      $backdropFile = $request->file('backdrop_file');
      $posterFile = $request->file('poster_file');

      $maxSize = env("MAX_FILE_SIZE") * 1024 * 1024;

      // Check max file size
      if ($backdropFile->getSize() > $maxSize || $posterFile->getSize() > $maxSize){
        return back()->withErrors([
          "backdrop_file" => "File tidak boleh lebih dari ".env("MAX_FILE_SIZE")." MB",
          "poster_file" => "File tidak boleh lebih dari ".env("MAX_FILE_SIZE")." MB",
        ]);
      }

      // Prepare save to database
      $data = new Movie;
      $data->title = $validatedData['title'];
      $data->overview = $validatedData['overview'];
      $data->release_date = $validatedData['release_date'];
      $data->vote_average = $validatedData['vote_average'];
      $data->runtime = $validatedData['runtime'];
      $data->youtube_id = $validatedData['youtube_id'];
      $data->type = $validatedData['type'];

      // Upload backdrop image
      $imageController = new ImageController;
      $backdropFileName = Carbon::now()->getTimestampMs().".webp";
      $backdropResult = $imageController->resizeImage($backdropFile, $backdropFileName);

      // Convert to webp, if failed store original file
      if ($backdropResult){
        $data->backdrop_path = asset("storage/{$backdropFileName}");
      } else {
        $uploadPath = $backdropFile->store("public");
        $pathArr = explode("/", $uploadPath);
        $data->backdrop_path = asset("storage/{$pathArr[1]}");
      }

      // Upload poster image
      $posterFileName = Carbon::now()->getTimestampMs().".webp";
      $posterResult = $imageController->resizeImage($posterFile, $posterFileName);

      // Convert to webp, if failed store original file
      if ($posterResult){
        $data->poster_path = asset("storage/{$posterFileName}");
      } else {
        $uploadPath = $posterFile->store("public");
        $pathArr = explode("/", $uploadPath);
        $data->poster_path = asset("storage/{$pathArr[1]}");
      }

      // Save to database
      $data->save();

      // Show notification
      toastr()->success('Data berhasil disimpan!');

      if ($data->type == 'movie'){
        return redirect()->route('movies.index');
      } else {
        return redirect()->route('tv_shows.index');
      }
    } else {
      return back()->withErrors([
        "backdrop_file" => "File tidak valid",
        "poster_file" => "File tidak valid",
      ]);
    }
  }

  public function movies_edit_form($id){
    $data = Movie::find($id);

    // If data not found, redirect to movie list
    if (!$data){
      return redirect()->route('movies.index');
    }

    return view('panel.movies.edit_form', [
      'data' => $data
    ]);
  }

  public function movies_update(Request $request){
    // Validate data
    $validatedData = $request->validate([
      'id' => 'required',
      'title' => ['required', 'string', 'max:255'],
      'overview' => ['required', 'string'],
      'release_date' => ['required', 'string'],
      'vote_average' => ['required', 'string'],
      'runtime' => ['required', 'string'],
      'youtube_id' => ['required', 'string'],
      'type' => ['in:movie,tv_show']
    ]);

    // Find data from database
    $savedData = Movie::find($validatedData['id']);

    // Prepare upload file
    $imageController = new ImageController;

    // Check is file available
    if ($request->file('backdrop_file') && $request->file('backdrop_file')->isValid()){
      $backdropFile = $request->file('backdrop_file');

      $backdropFileName = Carbon::now()->getTimestampMs().".webp";
      $backdropResult = $imageController->resizeImage($backdropFile, $backdropFileName);

      // Convert to webp, if failed store original file
      if ($backdropResult){
        $savedData->backdrop_path = asset("storage/{$backdropFileName}");
      } else {
        $uploadPath = $backdropFile->store("public");
        $pathArr = explode("/", $uploadPath);
        $savedData->backdrop_path = asset("storage/{$pathArr[1]}");
      }
    }

    // Check is file available
    if ($request->file('poster_file') && $request->file('poster_file')->isValid()){
      $posterFile = $request->file('poster_file');

      // Upload poster image
      $posterFileName = Carbon::now()->getTimestampMs().".webp";
      $posterResult = $imageController->resizeImage($posterFile, $posterFileName);

      // Convert to webp, if failed store original file
      if ($posterResult){
        $savedData->poster_path = asset("storage/{$posterFileName}");
      } else {
        $uploadPath = $posterFile->store("public");
        $pathArr = explode("/", $uploadPath);
        $savedData->poster_path = asset("storage/{$pathArr[1]}");
      }
    }

    // Update another data
    $savedData->title = $validatedData['title'];
    $savedData->overview = $validatedData['overview'];
    $savedData->release_date = $validatedData['release_date'];
    $savedData->vote_average = $validatedData['vote_average'];
    $savedData->runtime = $validatedData['runtime'];
    $savedData->youtube_id = $validatedData['youtube_id'];
    $savedData->type = $validatedData['type'];

    // Save to database
    $savedData->save();

    toastr()->success('Data berhasil diperbarui');

    if ($savedData->type == 'movie'){
      return redirect()->route('movies.index');
    } else {
      return redirect()->route('tv_shows.index');
    }
  }

  public function movies_delete($id){
    $data = Movie::find($id);

    if (!$data){
      return back();
    }

    $data->delete();
    toastr()->success('Data berhasil dihapus!');

    return back();
  }

  public function movies_import_form(){
    return view('panel.movies.import_form');
  }

  public function movies_import(Request $request){
    // Validate data
    $validatedData = $request->validate([
      'json' => ['required'],
    ]);

    $jsonData = json_decode($validatedData['json']);
    if($jsonData){
      $insertCount = 0;
      $alreadyExistedCount = 0;

      // Looping data
      foreach($jsonData->results as $item){
        $dbData = Movie::where('imdb_id', $item->id)->first();

        // Check already exists in database
        if (!$dbData){
          // Prepare save to database
          $movie = new Movie;
          $movie->imdb_id = $item->id;
          $movie->title = $item->title;
          $movie->overview = $item->overview;

          if (isset($item->release_date)){
            $movie->release_date = $item->release_date;
          } else {
            $movie->release_date = "";
          }

          $movie->vote_average = $item->vote_average;
          $movie->runtime = 0;
          $movie->type = "movie";

          if ($item->backdrop_path){
            $movie->backdrop_path = "https://image.tmdb.org/t/p/original".$item->backdrop_path;
          } else {
            $movie->backdrop_path = "";
          }

          if ($item->poster_path){
            $movie->poster_path = "https://image.tmdb.org/t/p/original".$item->poster_path;
          } else {
            $movie->poster_path = "";
          }

          // Save to database
          $movie->save();

          // Count
          $insertCount++;
        } else {
          $alreadyExistedCount++;
        }
      }

      // Show notification
      toastr()->success($insertCount." data berhasil ditambahkan");
      toastr()->success($alreadyExistedCount." data sudah ada di database");

      // Redirect to import form
      return redirect()->route('movies.import_form');
    } else {
      return back()->withErrors([
        "json" => "Data tidak dapat diproses karena bukan berbentuk JSON"
      ]);
    }
  }

  public function panel_tv_shows(){
    $tvShows = Movie::where('type', 'tv_show')->orderByDesc('updated_at')->paginate(10);
    return view('panel.tv_shows.index', [
      'tvShows' => $tvShows
    ]);
  }

  public function tv_import_form(){
    return view('panel.tv_shows.import_form');
  }

  public function tv_import(Request $request){
    // Validate data
    $validatedData = $request->validate([
      'json' => ['required'],
    ]);

    $jsonData = json_decode($validatedData['json']);
    if($jsonData){
      $insertCount = 0;
      $alreadyExistedCount = 0;

      // Looping data
      foreach($jsonData->results as $item){
        $dbData = Movie::where('imdb_id', $item->id)->first();

        // Check already exists in database
        if (!$dbData){
          // Prepare save to database
          $movie = new Movie;
          $movie->imdb_id = $item->id;
          $movie->title = $item->name;
          $movie->overview = $item->overview;

          if (isset($item->first_air_date)){
            $movie->release_date = $item->first_air_date;
          } else {
            $movie->release_date = "";
          }

          $movie->vote_average = $item->vote_average;
          $movie->runtime = 0;
          $movie->type = "tv_show";

          if ($item->backdrop_path){
            $movie->backdrop_path = "https://image.tmdb.org/t/p/original".$item->backdrop_path;
          } else {
            $movie->backdrop_path = ""; 
          }

          if ($item->poster_path){
            $movie->poster_path = "https://image.tmdb.org/t/p/original".$item->poster_path;
          } else {
            $movie->poster_path = "";
          }

          // Save to database
          $movie->save();

          // Count
          $insertCount++;
        } else {
          $alreadyExistedCount++;
        }
      }

      // Show notification
      toastr()->success($insertCount." data berhasil ditambahkan");
      toastr()->success($alreadyExistedCount." data sudah ada di database");

      // Redirect to import form
      return redirect()->route('tv_shows.import_form');
    } else {
      return back()->withErrors([
        "json" => "Data tidak dapat diproses karena bukan berbentuk JSON"
      ]);
    }
  }

  public function settings(){
    $user = User::where('id', auth()->user()->id)->first();

    return view('panel.settings.form', [
      "user" => $user
    ]);
  }

  public function settings_update(Request $request){
    // Prepare database
    $savedData = User::where('id', auth()->user()->id)->first();

    // Validate data
    $validatedData = $request->validate([
      'name' => ['required', 'string']
    ]);

    $savedData->name = $validatedData['name'];

    // Save to database
    $savedData->save();
    toastr()->success('Data berhasil diperbarui!');
    
    return redirect()->route('dashboard');
  }

  public function password(){
    return view('panel.password.form');
  }
}