<?php

class Reservations_Model extends MY_Model {
	
	protected $table = 'Reservations';
	protected $join  = array(
		'payments' => 'payments.id_reservation = reservations.id|left',
		'users' => 'users.id = reservations.id_user',
		'ships' => 'ships.id = reservations.id_ship'
	);
	protected $id    = 'id';
	protected $select = 'reservations.id, users.id as user_id, ships.id as ship_id, reservations.reservation_num, reservations.name, reservations.last_name, reservations.gross_rate, reservations.taxes, reservations.others, reservations.reservation_date, reservations.sailing_date, ships.name as ship_name';

}