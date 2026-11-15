<?php

use Illuminate\Support\Facades\Route;

// Si alguien abre el servidor directamente, se le manda al sitio
Route::redirect('/', 'https://joaquinsrg.github.io/visualizador-avance-curricular/');
