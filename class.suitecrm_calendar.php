<?php

/*****************************************************
* Requires 3 steps:

    Create ‘Meeting’ object
    Create ‘Meeting’ to ‘Contact’ relation
    Create ‘Meeting’ to ‘User’ relation

*****************************************************/

require_once( 'class.suitecrm_base.php' );	//class suitecrm

class SuitecrmCalendar extends suitecrmBase
{
	protected $date_start;
	protected $duration_minutes;
	protected $description;
	protected $name;
	protected $status; 		//Planned
	protected $parent_type;		//'Contacts'
	protected $parent_id;		//$contact_id
	protected $meeting_id;		//what is returned on the create....
	function __construct( $debug_level = PEAR_LOG_DEBUG, $param_arr )
	{
		parent::__construct( $debug_level, $param_arr );
		$this->set( "module_name", "Calendar" );
	}
	/*****************
* PERL generic relationship code...
sub create_module_links {
    my ($self, $module, $module_id, $link_field_name, $related_ids, $attributes) = @_;
 
    foreach my $required_attr (@{$self->required_attr->{$module}}) {
        $self->log->logconfess("No $required_attr attribute. Not creating links in $module for: ".Dumper($attributes))
            if (!exists($$attributes{$required_attr}) ||
                !defined($$attributes{$required_attr}) ||
                $$attributes{$required_attr} eq '');
    }
 
    my $rest_data = '{"session": "'.$self->sessionid.'", "module_name": "'.$module
        . '", "module_id": "' . $module_id . '", "link_field_name": "'
        . $link_field_name. '", "related_ids": ' . encode_json($related_ids)
        . ', "name_value_list": '. encode_json($attributes). '}';
 
    my $response = $self->_rest_request('set_relationship', $rest_data);
    $self->log->info( "Successfully created link in <".encode_json($attributes)."> with sessionid ".$self->sessionid."\n");
    $self->log->debug("Link created in module $module was:".Dumper($response));
    #return $response->{id};
    return $response;
*******************/

/************************************
*the 2 steps for relationships...

my $link_entry = {
        module => 'Meetings',
        meeting_id => $meeting_id,
        module => 'Contacts',
        contact_id => $contact_list[$i]->{'id'}
    };
 
my $out = $s->create_module_links('Meetings',$meeting_id,'contacts',[$contact_id],$link_entry);
 
my $user_link_entry = {
    meeting_id => $meeting_id,
    user_id => $user_id,
    accept_status => 'accept',
    required => 1
};
 
$out = $s->create_module_links('Meetings',$meeting_id,'users',[$user_id],$user_link_entry);

***************************************************/


}

/*
if($cl->session_id){
	$set_relationship_parameters = array(
		'session' => $session_id,
		'module_name' => 'Meetings',
		'module_id' => 'ede7c2ad-f180-39ef-571a-563b6ab6f10e',
		'link_field_name' => 'users',
		'related_ids' => array(
			'1',
		),
		'name_value_list' => array(
			array(
				'name' => 'accept_status',
				'value' => 'none'
			),
		),
		'delete'=> 0,
	);
	$set_relationship_result = call('set_relationship', $set_relationship_parameters, $url);
	print_r($set_relationship_result);
}

/******** Meetings
$set_contact_parameters = array (
                'session' => $data->id,


                'module_name' => 'Meetings',


                'name_value_list' => array( array (
                    "name" => "name",
                    "value" => "Subject" 
            ),
            array (
                    "name" => "description",
                    "value" => "description" 
            ),

            array (
                    "name" => "location",
                    "value" => "Pune" 
            ),

            array (
                    "name" => "duration_hours",
                    "value" =>"1" 
            ),
                ) );
 $dataMeeting = call ( "set_entry", $set_contact_parameters, $url );

 $parameters = array(
                'session' =>  $data->id,
                'module_name' => 'Meetings',
                'module_id' => $dataMeeting->id,
                'link_field_name' => 'contacts',
                'related_ids ' => array('25627846-a8a2-eeb5-3565-532035113842'),
 );

$dataContactMeetings = call ( "set_relationship", $parameters, $url );

*/

/*
{
 'assigned_user_id': '1', # My user's ID
 'date_end': '2013-04-16 01:30:00',
 'date_start': '2013-04-16 01:23:45',
 'description': 'hello world',
 'location': 'JCenter',
 'name': 'Test',
 'team_id': '1',
 'type': 'Sugar' # This doesn't seem to be required
}

Also needs to set user_id so it appears in user calendar?
*/
