<?php
  
namespace App\Enums;
 
enum ClientRequestsStatusEnum:string {
    case Active = 'Active';
    case Resolved = 'Resolved';
}