<?php
namespace Slimwp\Core\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
/**
 * Middleware.
 */
final class LocaleSessionMiddleware implements MiddlewareInterface
{
    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     *
     * @return ResponseInterface The response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO: Read the user language from the session or other parameters
        $locale = $_SESSION['locale'] ?? 'en_US';
        $locale = 'cs_CZ';
        $request = $request->withAttribute('locale', $locale);
        return $handler->handle($request);
    }
}
