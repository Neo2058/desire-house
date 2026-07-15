<?php

namespace App\Enums;


enum LeadStatus:string
{

    case NEW = 'new';

    case IN_PROGRESS = 'in_progress';

    case CLARIFICATION = 'clarification';

    case PAYMENT_WAITING = 'payment_waiting';

    case COMPLETED = 'completed';

    case CANCELLED = 'cancelled';


    public function label():string
    {

        return match($this){

            self::NEW =>
            'Новая',

            self::IN_PROGRESS =>
            'В работе',

            self::CLARIFICATION =>
            'На уточнении',

            self::PAYMENT_WAITING =>
            'Ожидает оплаты',

            self::COMPLETED =>
            'Обработано',

            self::CANCELLED =>
            'Отказ',

        };

    }

}
