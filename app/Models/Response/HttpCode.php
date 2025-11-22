<?php
declare(strict_types=1);

namespace App\Models\Response;

/**
 * Class containing constants of HTTP Status Codes
 * and method to print HTTP code with its description.
 *
 * Usage:
 *
 * ```php
 * <?php
 * use \Codeception\Util\HttpCode;
 *
 * // using REST, PhpBrowser, or any Framework module
 * $I->seeResponseCodeIs(HttpCode::OK);
 * $I->dontSeeResponseCodeIs(HttpCode::NOT_FOUND);
 * ```
 */
class HttpCode
{
    public const int CONTINUE = 100;
    public const int SWITCHING_PROTOCOLS = 101;
    public const int PROCESSING = 102;            // RFC2518
    public const int EARLY_HINTS = 103;           // RFC8297
    public const int OK = 200;
    public const int CREATED = 201;
    public const int ACCEPTED = 202;
    public const int NON_AUTHORITATIVE_INFORMATION = 203;
    public const int NO_CONTENT = 204;
    public const int RESET_CONTENT = 205;
    public const int PARTIAL_CONTENT = 206;
    public const int MULTI_STATUS = 207;          // RFC4918
    public const int ALREADY_REPORTED = 208;      // RFC5842
    public const int IM_USED = 226;               // RFC3229
    public const int MULTIPLE_CHOICES = 300;
    public const int MOVED_PERMANENTLY = 301;
    public const int FOUND = 302;
    public const int SEE_OTHER = 303;
    public const int NOT_MODIFIED = 304;
    public const int USE_PROXY = 305;
    public const int RESERVED = 306;
    public const int TEMPORARY_REDIRECT = 307;
    public const int PERMANENTLY_REDIRECT = 308;  // RFC7238
    public const int BAD_REQUEST = 400;
    public const int UNAUTHORIZED = 401;
    public const int PAYMENT_REQUIRED = 402;
    public const int FORBIDDEN = 403;
    public const int NOT_FOUND = 404;
    public const int METHOD_NOT_ALLOWED = 405;
    public const int NOT_ACCEPTABLE = 406;
    public const int PROXY_AUTHENTICATION_REQUIRED = 407;
    public const int REQUEST_TIMEOUT = 408;
    public const int CONFLICT = 409;
    public const int GONE = 410;
    public const int LENGTH_REQUIRED = 411;
    public const int PRECONDITION_FAILED = 412;
    public const int REQUEST_ENTITY_TOO_LARGE = 413;
    public const int REQUEST_URI_TOO_LONG = 414;
    public const int UNSUPPORTED_MEDIA_TYPE = 415;
    public const int REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    public const int EXPECTATION_FAILED = 417;
    public const int I_AM_A_TEAPOT = 418;                                               // RFC2324
    public const int MISDIRECTED_REQUEST = 421;                                         // RFC7540
    public const int UNPROCESSABLE_ENTITY = 422;                                        // RFC4918
    public const int LOCKED = 423;                                                      // RFC4918
    public const int FAILED_DEPENDENCY = 424;                                           // RFC4918
    public const int RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL = 425;   // RFC2817
    public const int UPGRADE_REQUIRED = 426;                                            // RFC2817
    public const int PRECONDITION_REQUIRED = 428;                                       // RFC6585
    public const int TOO_MANY_REQUESTS = 429;                                           // RFC6585
    public const int REQUEST_HEADER_FIELDS_TOO_LARGE = 431;                             // RFC6585
    public const int UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    public const int INTERNAL_SERVER_ERROR = 500;
    public const int NOT_IMPLEMENTED = 501;
    public const int BAD_GATEWAY = 502;
    public const int SERVICE_UNAVAILABLE = 503;
    public const int GATEWAY_TIMEOUT = 504;
    public const int VERSION_NOT_SUPPORTED = 505;
    public const int VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;                        // RFC2295
    public const int INSUFFICIENT_STORAGE = 507;                                        // RFC4918
    public const int LOOP_DETECTED = 508;                                               // RFC5842
    public const int NOT_EXTENDED = 510;                                                // RFC2774
    public const int NETWORK_AUTHENTICATION_REQUIRED = 511;                             // RFC6585

    /**
     * Описания кодов
     *
     * @var array<int, string>
     */
    private static array $codes = [
        100 => 'Continue',
        102 => 'Processing',
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'Unassigned',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    /**
     * Returns string with HTTP code and its description
     *
     * ```php
     * <?php
     * HttpCode::getDescription(200); // '200 (OK)'
     * HttpCode::getDescription(401); // '401 (Unauthorized)'
     * ```
     *
     * @param int $code
     * @return string|int
     */
    public static function getDescription(int $code): int|string
    {
        if (isset(self::$codes[$code])) {
            return sprintf('%d (%s)', $code, self::$codes[$code]);
        }

        return $code;
    }
}
