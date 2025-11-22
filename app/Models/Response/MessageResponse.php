<?php
declare(strict_types=1);

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models\Response;

use OpenApi\Attributes as OA;

/**
 * Description of MessageResponse
 *
 * @author Mansur
 */
#[OA\Schema]
class MessageResponse
{
    #[OA\Property(description: 'Сообщение')]
    public string $message;

    /**
     * Constructor
     *
     * @param string $message
     */
    public function __construct(string $message = '')
    {
        $this->message = $message;
    }
}
