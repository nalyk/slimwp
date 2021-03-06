<?php
use Slimwp\Core\Controllers\Accounts;
use Slimwp\Core\Controllers\AuthController;
use Slimwp\Core\Controllers\DomainsController as Domains;
use Slimwp\Core\Controllers\Slimwp;
use Slimwp\Core\Controllers\SlimwpApi;
use Slimwp\Core\Controllers\Profiles;
use Slimwp\Core\Controllers\ProfilesApi;
use Slimwp\Core\Middleware\RedirectAuthenticated;
use Slimwp\Core\Middleware\RedirectGuests;
use Slimwp\Core\Middleware\RestrictGuests;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;


// Define the app routes.
$app->get('/', Slimwp::class . ':index')->setName('core.get.home');
$app->get ('/post/{slug}', Slimwp::class . ':post_get')->setName('core.get.post');
$app->get ('/data/post/{slug}', Slimwp::class . ':ajax_post_get')->setName('core.ajax.get.post');

$app->get ('/ajax/post/{slug}', Slimwp::class . ':ajax_post_get')->setName('core.ajax.get.post');

$app->get ('/ajax/modal/post/{slug}', Slimwp::class . ':ajax_modal_post_get')->setName('core.ajax.modal.get.post');

$app->get ('/core/signout', AuthController::class . ':signout_get')->setName('core.signout.web');


$app->group('/core', function (RouteCollectorProxy $route) {
    $route->group('', function (RouteCollectorProxy $route) {
        $route->get('/dashboard', Slimwp::class)->setName('core.dashboard.web');
        $route->get('/profiles[/{uid}]', Profiles::class)->setName('core.profiles.list.web');
        $route->get('/accounts', Accounts::class . ':list') ->setName('core.accounts.list.web');
        $route->get('/accounts/{uid:[0-9]+}', Accounts::class . ':read')->setName('core.accounts.read.web');
        $route->patch('core/accounts/{uid:[0-9]+}/password', AuthController::class . ':change_password')-> setName('core.settings.password.web');
        $route->get('/domains', Domains::class . ':ui_manage')->setName('core.domains');
        $route->get ('/admin/phpinfo', function(Request $request, Response $response) { phpinfo(); return $response; }) -> setName('core.admin.phpinfo.web');
        $route->get ('/admin/phpconst', function(Request $request, Response $response) { highlight_string("<?php\nget_defined_constants() =\n" . var_export(get_defined_constants(true), true) . ";\n?>"); return $response; }) -> setName('core.admin.phpconst.web');
    })->add(RedirectGuests::class);
    $route->group('', function ($route) {
        $route->get ('/signin', AuthController::class . ':signin_get')->setName('core.signin.web');
        $route->post('/signin', AuthController::class . ':signin_post');
        $route->get ('/signup', AuthController::class . ':signup_get')->setName('core.signup.web');
        $route->post('/signup', AuthController::class . ':signup_post');
    })->add(RedirectAuthenticated::class);
});


$app->group('/api/core/v1', function (RouteCollectorProxy $route) {
    $route->get ('/test', SlimwpApi::class) ->                        setName('core.api');
    $route->post('/profiles', ProfilesApi::class . ':create') ->     setName('core.profiles.create.api01');
    $route->get ('/profiles', ProfilesApi::class . ':list') ->       setName('core.profiles.list.api01');
    $route->get ('/profiles/{uid:[0-9]+}', 'ApiProfiles:read') ->    setName('core.profiles.read.api01');
    $route->put ('/profiles/{uid:[0-9]+}', 'ApiProfiles:update') ->  setName('core.profiles.update.api01');
    $route->get ('/domains', Domains::class . ':list') ->            setName('core.domains.api01');
    $route->post('/domains', Domains::class . ':create');
})->add(RestrictGuests::class);


$app->get ('/core/admin/playground', function(Request $request, Response $response) { 

    $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES); // 256 bit
    
    echo sodium_bin2base64(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES), SODIUM_BASE64_VARIANT_URLSAFE);
    echo ' '.time();
    echo '
        <link rel="stylesheet" type="text/css" href="/assets/cache/styles.db34ce26cb19c04c315933041af50e77c292abb9.css" media="all" />
        <div id="validation_errors"></div>

        <div class="card card-primary col-4">
          <form action="/core/admin/playground" method="post" autocomplete="off" id="formpost">
            <input type="email" name="email" id="email" placeholder="you@domain.com" class="form-control" value="a@a.a">
            <input type="password" name="password" id="password" class="form-control" value="pwpw">
            <button type="submit" class="btn btn-primary">Form submit</button>
        </form>
        <form action="/core/admin/playground" method="post" autocomplete="off" id="formajax">
            <input type="email" name="email" id="email" placeholder="you@domain.com" class="form-control" value="a@a.a">
            <input type="password" name="password" id="password" class="form-control" value="pwpw">
            <button type="submit" class="btn btn-primary">Ajax submit</button>
        </form>
        <div id="twigAplaceholder"></div>
        {% raw %}
        <script type="text/twig" id="twigA">
            <form action="/core/admin/playground" method="post" autocomplete="off" id="formajaxput">
                <input type="email" name="email" id="email_input" placeholder="you@domain.com" class="form-control{{ validation_errors.email ? \' is-invalid\' : validation_reseed.email ? \' is-valid\' : \'\' }}" value="{{ validation_reseed.email }}">
                {% if validation_errors.email %}
                    <span class="invalid-feedback">{{ validation_errors.email | first }}</span>
                {% endif %}
                <input type="password" name="password" id="password" class="form-control" value="pwpw">
                <button type="submit" class="btn btn-primary">Ajax submit PUT</button>                    
            </form>            
        </script>
        {% endraw %}
        <br>twig.js tests<br><br>
        <script type="text/twig" id="twigB">
          {% set animal = "fox" %}
          a quick brown {{ animal }}
          {{ validation_errors.email }}
          {{ validation_errors.name }}
          {{ validation_errors.password }}
          {{ list.1 }}

          <hr>
        </script>
        <div class="display-templates"></div>
        <script src="/assets/node_modules/jquery/dist/jquery.min.js" nonce="dummy_nonce"></script>
        <script src="/assets/node_modules/twig/twig.min.js" nonce="dummy_nonce"></script>
        <script src="/assets/node_modules/@claviska/jquery-ajax-submit/jquery.ajaxSubmit.min.js" nonce="dummy_nonce"></script>

        <script nonce="dummy_nonce">
            $("#formajax").ajaxSubmit({
              success: function(res) {
                console.log(res);
                location.reload();
              }
            });

            $("script[type=\'text/twig\']").each(function() {
              var id = $(this).attr("id"),
                data = $(this).text();

              Twig.twig({
                id: id,
                data: data,
                allowInlineIncludes: true
              });
            });

            var listjs = ["one", "two", "three"];

            $(".display-templates").append(
              Twig.twig({ ref: \'twigB\' }).render({ list: listjs })
            );

            $("#twigAplaceholder").append(
              Twig.twig({ ref: \'twigA\' }).render({ list: listjs })
            );
        </script>            

        <script nonce="dummy_nonce">
            $("#formajaxput").ajaxSubmit({
                headers: {
                    "X-Http-Method-Override": "PUT"
                },                    
                success: function(res) {
                    console.log(res);
                },
                error: function(res) {
                    $("#validation-errors").html("");
                    $("#validation_errors").empty();
                    $.each(res.message.validation_errors, function(key,value) {
                        $("#validation_errors").append("<div class=\'alert alert-danger\'>"+key+\' \'+value[0]+"</div");
                    }); 
                    $(".display-templates").append(
                        Twig.twig({ ref: \'twigB\' }).render({ validation_errors: res.message.validation_errors })
                    );
                    $(".display-templates").replaceWith(
                        Twig.twig({ ref: \'twigA\' }).render({ validation_errors: res.message.validation_errors })
                    );
                }
            });
        </script>
        ';

    return $response;
}) -> setName('core.admin.playground.web');

$app->post ('/core/admin/playground', function(Request $request, Response $response) { 
    $e = $request->getParam('email');
    $p = $request->getParam('password');
    $isXHR = $request->isXhr();
    $gpb = $request->getParsedBody();
    $upb = $request->getBody();
    $x = array( 'e' => $e, 'p' => $p, 'xhr' => $isXHR, 'gpb' => $gpb, 'upb' => htmlentities($upb) );
    if ($isXHR === true) {
        return $response->withJson($x);
    } else {
        //echo $upb;
        return $response->withJson($x);
        //return $response->withRedirect('http://10.146.149.29/core/admin/playground');
    }    
});

$app->put ('/core/admin/playground', function(Request $request, Response $response) { 
    //validation_errors
    //$x = array( 'put' => 'success' );
    $x = [
            'success' => 'false', 
            'message' => [ 
                'validation_errors' => [ 
                    'password' => [ 'must be longer', 'must be stronger' ],
                    'email' => [ 'must be nicer' ],
                ]
            ]
        ];
    return $response->withJson($x)->withStatus(400);
});


