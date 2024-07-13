<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
  public function index(){
    $bannerArray = Movie::orderByDesc('updated_at')
      ->limit(3)
      ->get();
    
    $topMoviesArray = Movie::where('type', 'movie')
      ->orderByDesc('vote_average')
      ->limit(10)
      ->get();

    $topTVShowsArray = Movie::where('type', 'tv_show')
      ->orderByDesc('vote_average')
      ->limit(10)
      ->get();

    // Show home.blade.php with additional data
    return view('home', [
      'banner' => $bannerArray,
      'topMovies' => $topMoviesArray,
      'topTVShows' => $topTVShowsArray
    ]);
  }

  public function movies(Request $request){
    $sortBy = $request->query('sort_by');

    // Default sort
    if (!$sortBy){
      $sortBy = "views.desc";
    } else if (!($sortBy == 'views.desc' || $sortBy == 'views.asc' || $sortBy == 'vote_average.desc' || $sortBy == 'vote_average.asc')){
      $sortBy = "views.desc";
    }

    // Split sort data by "."
    // [0] => 'views'
    // [1] => 'desc'
    $sortByArr = explode(".", $sortBy);

    $movieArray = Movie::where('type', 'movie')
      ->orderBy($sortByArr[0], $sortByArr[1])
      ->paginate(10);

    // Show movie.blade.php with additional data
    return view('movie', [
      'movies' => $movieArray,
      'sortBy' => $sortBy
    ]);
  }

  public function tvShows(Request $request){
    $sortBy = $request->query('sort_by');

    // Default sort
    if (!$sortBy){
      $sortBy = "views.desc";
    } else if (!($sortBy == 'views.desc' || $sortBy == 'views.asc' || $sortBy == 'vote_average.desc' || $sortBy == 'vote_average.asc')){
      $sortBy = "views.desc";
    }

    // Split sort data by "."
    // [0] => 'views'
    // [1] => 'desc'
    $sortByArr = explode(".", $sortBy);

    $tvArray = Movie::where('type', 'tv_show')
      ->orderBy($sortByArr[0], $sortByArr[1])
      ->paginate(10);

    // Show tv.blade.php with additional data
    return view('tv', [
      'tvShows' => $tvArray,
      'sortBy' => $sortBy
    ]);
  }

  public function search(Request $request){
    $keyword = $request->query('keyword');
    $searchResults = [];

    if (isset($keyword)){
      $keyword = preg_replace('/([^a-zA-Z0-9])/', '', $keyword);

      $searchResults = Movie::where('title', 'like', '%'.$keyword.'%')
        ->orWhere('overview', 'like', '%'.$keyword.'%')
        ->orderBy('title')
        ->get();
    }

    // Show search.blade.php with additional data
    return view('search', [
      'keyword' => $keyword,
      'searchResults' => $searchResults
    ]);
  }

  public function movieDetails(Request $request, $id){
    // Find data by UUID
    $movieData = Movie::find($id);

    // Redirect to home page if data not found
    if (!$movieData){
      return redirect("/");
    }

    // Hit API if youtube ID is empty and from IMDB data
    if (!$movieData->youtube_id && $movieData->imdb_id){
      // Get environment variable
      $baseURL = env('MOVIE_DB_BASE_URL');
      $apiKey = env('MOVIE_DB_API_KEY');

      // Hit API data
      $response = Http::get("{$baseURL}/movie/{$movieData->imdb_id}", [
        'api_key' => $apiKey,
        'append_to_response' => 'videos'
      ]);

      // Check API response
      if ($response->successful()){
        $responseData = $response->object();

        // Check youtube ID
        $trailerID = "";
        if (isset($responseData->videos->results)){
          foreach($responseData->videos->results as $item){
            if (strtolower($item->type) == 'trailer'){
              $trailerID = $item->key;
              break;
            }
          }
        }

        // Set youtube ID
        $movieData->youtube_id = $trailerID;

        // Set total duration
        if (isset($responseData->runtime)){
          $movieData->runtime = $responseData->runtime;
        }

        // Save to database
        $movieData->save();
      }
    }

    // Update views data if session not exists
    // Session lifetime is 120 minutes (Check SESSION_LIFETIME in .env)
    $sessionName = "details_".$id;
    if (!$request->session()->get($sessionName)){
      $request->session()->put($sessionName, true);

      // Increase views data
      $movieData->views = $movieData->views + 1;
      
      // Save to database
      $movieData->save();
    }

    // Show movie_details.blade.php with additional data
    return view('movie_details', [
      'movieData' => $movieData
    ]);
  }

  public function tvDetails(Request $request, $id){
    // Find data by UUID
    $movieData = Movie::find($id);

    // Redirect to home page if data not found
    if (!$movieData){
      return redirect("/");
    }

    // Hit API if youtube ID is empty and from IMDB data
    if (!$movieData->youtube_id && $movieData->imdb_id){
      // Get environment variable
      $baseURL = env('MOVIE_DB_BASE_URL');
      $apiKey = env('MOVIE_DB_API_KEY');

      // Hit API data
      $response = Http::get("{$baseURL}/tv/{$movieData->imdb_id}", [
        'api_key' => $apiKey,
        'append_to_response' => 'videos'
      ]);

      // Check API response
      if ($response->successful()){
        $responseData = $response->object();

        // Check youtube ID
        $trailerID = "";
        if (isset($responseData->videos->results)){
          foreach($responseData->videos->results as $item){
            if (strtolower($item->type) == 'trailer'){
              $trailerID = $item->key;
              break;
            }
          }
        }

        // Set youtube ID
        $movieData->youtube_id = $trailerID;

        // Set total duration
        if (isset($responseData->runtime)){
          $movieData->runtime = $responseData->runtime;
        }

        // Save to database
        $movieData->save();
      }
    }

    // Update views data if session not exists
    // Session lifetime is 120 minutes (Check SESSION_LIFETIME in .env)
    $sessionName = "details_".$id;
    if (!$request->session()->get($sessionName)){
      $request->session()->put($sessionName, true);

      // Increase views data
      $movieData->views = $movieData->views + 1;
      
      // Save to database
      $movieData->save();
    }

    // Show movie_details.blade.php with additional data
    return view('movie_details', [
      'movieData' => $movieData
    ]);
  }
}
