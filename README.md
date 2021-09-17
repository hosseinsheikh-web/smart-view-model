<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


# <font color='yellow'>***Smart View Model***</font> 
- To use this pattern, you can do the following
    - Make controller and view model:
        
            php artisan vm:make-controller {controller name}
            php artisan vm:make-controller {controller name} --namespace={defin special namespace for viewmodel}
            php artisan vm:make-viewmodel {view model name} --controller={controller name}
            php artisan vm:make-viewmodel {view model name} --controller={controller name} --namespace={defin special namespace for viewmodel}

- Controller method structure:
              
             // view model puts in ViewModel/Hossein namespace
             // view model name is IndexViewModel
              public function index()
              {
                  return \SmartViewModel::addItems([])
                      ->throughViewModel('hossein.index') 
                      ->thenReturn();
              }
  
            // view model puts in Malekkhatoon/Ahmad/ViewModel/Hossein namespace
            // view model name is IndexViewModel
            public function index()
            {
                return \SmartViewModel::addItems([])
                    ->throughViewModel('hossein.index')
                    ->setNameSpace('malekhatoon.ahmad')
                    ->thenReturn();
            }

            // You can use this instead of the top
            public function index()
            {
                return \SmartViewModel::addItems([])
                    ->through(Malekkhatoon\Ahmad\ViewModel\Hossein\IndexViewModel::class)
                    ->thenReturn();
            }

    
    


