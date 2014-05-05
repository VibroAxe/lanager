<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Bans
	|--------------------------------------------------------------------------
	|
	| List the database IDs of users who should not be allowed to perform an
	| action on a resource
	|
	*/
	// 'banned' => array(
	// 	'create' => array(
	// 		'playlist.items' => array(0),
	// 		'shouts' => array(0),
	// 	),
	// ),
	
	/*
	|--------------------------------------------------------------------------
	| Initialisation
	|--------------------------------------------------------------------------
	|
	| Be careful editing here - you could open up the system to serious abuse!
	| The order in which the permissions are set is important
	|
	*/
	'initialise' => function($authority)
	{
		$authority->addAlias('manage', array('create', 'read', 'update', 'delete'));
		$self = $authority->getCurrentUser();

		// If there is a user currently logged in, assign them permissions
		if ( is_object($self) )
		{
			// Allow any logged in user to...
			$authority->allow('create', 'shouts');
			$authority->allow('create', 'items');
			$authority->allow('create', 'skipvotes');
			
			$authority->allow('delete', 'users', function($self, $user) 
			{
				if ( is_object($user) )
				{
					return $self->getCurrentUser()->id === $user->id; // passed entire user object
				}
				else
				{
					return $self->getCurrentUser()->id === $user; // just passed user id
				}
			});

			// Deny based on bans
			// if( in_array($self->id, Config::get('lanager/permissions.banned.create.shouts')) )
			// {
			// 	$authority->deny('create', 'shouts');
			// }
			// if( in_array($self->id, Config::get('lanager/permissions.banned.create.playlist.items')) )
			// {
			// 	$authority->deny('create', 'playlist.items');
			// }
			
			// Assign extra permissions based on user's roles
			if ( $self->hasRole('InfoPagesAdmin') ) 
			{
				$authority->allow('manage', 'infopages');
			}

			if ( $self->hasRole('ShoutsAdmin') ) 
			{
				$authority->allow('manage', 'shouts');
			}

			if ( $self->hasRole('EventsAdmin') ) 
			{
				$authority->allow('manage', 'events');
			}

			if ( $self->hasRole('PlaylistsAdmin') ) 
			{
				$authority->allow('manage', 'playlists');
				$authority->allow('manage', 'items');
			}

			// Must be at bottom
			if ( $self->hasRole('SuperAdmin') ) 
			{
				$authority->allow('manage', 'all');
			}
		}
	}
);