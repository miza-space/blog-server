<?php
// homepage
Route::get("/{page?}", array("as" => "index", "uses" => "PageController@index"))->where('page', '[0-9]+');

Route::get("/api/blogs/{skip?}/{take?}", array("as" => "index", "uses" => "APIController@blogs"))->where(array('skip' => '[0-9]+', 'take' => '[0-9]+'));

// profile
Route::get("/profile", array("as" => "profile", "uses" => "PageController@profile"));

// picture list
Route::get("/picture", array("as" => "picture", "uses" => "PageController@picture"));

// favor music
Route::get("/music", array("as" => "music", "uses" => "PageController@music"));

// funny labs
Route::get("/labs", array("as" => "labs", "uses" => "PageController@labs"));

// weixin post 
Route::post("/labs/weixin", array("as" => "labs_weixin", "uses" => "LabsController@weixin"));

// fetching media from outside
Route::get("/labs/fetch/media/{source}", array("as" => "labs_fetch_media", "uses" => "LabsController@fetchMedia"));