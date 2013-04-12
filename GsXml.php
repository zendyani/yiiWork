<?php
/* 
 * Class to manage gsm api in soccer sport 
 *
 * @author Belakhdar Abdeldjalil<zendyani@gmail.com>
 * @license 
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; version 2
 * of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * example of use
 * Get clubs by competition id
 * 
 * // init class
 * $s=new GsXml;
 * // make the call
 * $s->getSaisons($id,'competition');
 * data have to be casted with (int) for number or (string) for text to eliminate simpleXmlElement header
 * // get the result
 * $seasonId=(int)$s->getResult()->competition->season['season_id']; 
 */
		
class GsXml
{
	public $config;
	public $link;
	public $function;
	public $params=null;
	public $xml_result=null;
	
	/*
	 * Configuration parameters
	 */ 
	public function __construct(){
		$conf['link']='http://api.globalsportsmedia.com';	
		
		$conf['key']='';	// key can be found on your gsm account
		$conf['sport']='soccer';	// what type sport (soccer,....)
		$conf['lang']='';	// type of language
		$conf['username']='';	// your gsm username
		
		$conf['params']='params';
		$this->config=$conf;	
	}
	
	/*
	 * Get xml file from specifique params
	 */ 
	public function getXml(){
		
		//generate link
		$url[]=$this->config['link'].'/';
		$url[]=$this->config['sport'].'/';
		$url[]=$this->function;
		$url[]='?authkey='.$this->config['key'];
		$url[]='&username='.$this->config['username'];
		if(!empty($this->config['lang']))
			$url[]='&lang='.$this->config['lang'];
		
		if(!empty($this->params))
			$url[]=$this->params;
		
		$url=implode('',$url);
		$this->link=$url;

		try{
			$this->xml_result = @simplexml_load_file($url,'SimpleXMLElement');			
		}catch(Exception $e){
			echo 'Caught exception: ',  $e->getMessage(), "\n";	
		}
	}

	/*
	 * get result of xml file
	 */ 
	public function getResult(){
		return $this->xml_result;
	}
	
	/*
	 * Returns all areas, which can be 'world', continents and countries.
	 */ 	
	public function getAreas($area_id=null){
		
		$this->params='';
		
		if($area_id!==null)
			$this->params='&area_id='.$area_id;
		$this->function='get_areas';
		$this->getXml();	
	}

	/*
	 * Returns all competitions from an area.
	 */ 	
	public function getCompetitions($area_id=null,$authorized="yes"){
		
		$this->params='&authorized='.$authorized;
		
		if($area_id!==null)
			$this->params.='&area_id='.$area_id;
			
		$this->function='get_competitions';
		$this->getXml();
	}

	/*
	 * Returns all (authorized) seasons (that have been recently updated).
	 * @param: coverage : See what do we cover (kinds of data) for specific season.
	 * @param: active : only return seasons that are currently active, either 'yes' or 'no'
	 * @param: id to get seasons from
	 * @param: type : either 'competition' or 'team'
	 * @param: last_updated : Only get seasons last updated after given date, format 'yyyy-mm-dd hh:mm:ss'
	 */ 	
	public function getSaisons($id=null,$type=null,$last_updated=null){
		
		$this->params='&authorized=yes';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
		if($last_updated!==null)
			$this->params.='&last_updated='.$last_updated;
		
		
		$this->function='get_seasons';
		$this->getXml();
	}


	/*
	 * Returns a list of team (details) selected from area, season, round, group or team.
	 * @params: id = id belonging to the selection type chosen
	 * @params: type = selection type, one of 'area', 'season', 'round', 'group' or 'team' 
	 * @params: detailed = show all details, either 'yes' or 'no'
	 */ 	
	public function getTeams($id=null,$type=null,$detailed='no'){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
		if($detailed!==null)
			$this->params.='&detailed='.$detailed;
						
		$this->function='get_teams';
		$this->getXml();
	}
	
	/*
	 * Returns the league, form, home/away and over/under tables of a season or round.
	 * @params: id = id belonging to the selection type chosen
	 * @params: type = selection type, currently only 'season' and 'round' are possible 
	 * @params: tabletype = only get one tabletype, one of 'total', 'home', 'away', 'form-total', 'form-home', 'form-away' or 'overunder'
	 */ 	
	public function getTables($id=null,$type=null,$tabletype=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
		if($tabletype!==null)
			$this->params.='&tabletype='.$tabletype;
						
		$this->function='get_tables';
		$this->getXml();
	}
	
	/*
	 * Returns list of venues (stades) linked to area, season, team or venue.
	 * Required:
	 * @params: id = id belonging to the selection type chosen
	 * @params: type = selection type, one of 'area', 'season', 'team', 'venue'
	 * Optional:
	 * @params: detailed = show all details, either 'yes' or 'no'
	 */ 	
	public function getVenues($id=null,$type='season',$detailed='no'){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
		if($detailed!==null)
			$this->params.='&detailed='.$detailed;
						
		$this->function='get_venues';
		$this->getXml();
	}

	/*
	 * Returns player(s) current and past memberships with season statistics and shirtnumber.
	 * Required:
	 * @params: id = id belonging to the selection type chosen
	 * @params: type = selection type, one of 'player', 'team'
	 * Optional:
	 * @params: detailed = show all player details, either 'yes' or 'no'
	 * @params: active = show only active players of a team, either 'yes' or 'no' and defaults to 'yes'. 
	 * 					If no is selected, any player that we know has ever played at the team will be returned
	 * @params: range = range of career query, possible values: 'league' (default), 'cups', 'national', 'all'
	 */ 	
	public function getCareer($id=null,$type=null,$detailed=null,$active=null,$range=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
		if($detailed!==null)
			$this->params.='&detailed='.$detailed;
		if($active!==null)
			$this->params.='&active='.$active;
		if($range!==null)
			$this->params.='&range='.$range;
						
		$this->function='get_career';
		$this->getXml();
	}

	/*
	 * Returns all items (area, competition, season, round, group, match, event, team, person) which were deleted from our database.
	 * Optional:
	 * @params: type = Type of deleted items to select. 
	 * 				If not specified, only items from the last hour may be retrieved. Types include event, match, group, round, season, competition, area, person and team. 
	 * 				If no type is selected, each type which has a deletion is returned.
	 * @params: start_date = Select only items which were deleted after a certain time (rounded down to the hour). 
	 * 						This time may be at most 24 hours ago.
	 */ 	
	public function getDeleted($type=null,$start_date=null){
		
		$this->params='';
		
		if($type!==null)
			$this->params.='&type='.$type;
		if($start_date!==null)
			$this->params.='&start_date='.$start_date;
						
		$this->function='get_deleted';
		$this->getXml();
	}

	/*
	 * Returns all groups belonging to a round.
	 * Required:
	 * @params: round_id = round to select
	 */ 	
	public function getGroups($round_id=null){
		
		$this->params='';
		
		if($round_id!==null)
			$this->params.='&round_id='.$round_id;
						
		$this->function='get_groups';
		$this->getXml();
	}

	/*------------------------------------------------------------------
	 * Returns the h2h statistics and matches between two teams.
	 * Required:
	 * @params: team_1_id = first team to compare
	 * @params: team_2_id = second team to compare
	 */ 	
	public function getHead2HeadStatistics($team_1_id=null,$team_2_id=null){
		
		$this->params='';
		
		if($team_1_id!==null)
			$this->params.='&team_1_id='.$team_1_id;
		if($team_2_id!==null)
			$this->params.='&team_2_id='.$team_2_id;
						
		$this->function='get_head_2_head_statistics';
		$this->getXml();
	}

	/*
	 * Returns a list of injured players per competition, player, match or team.
	 * Required:
	 * @params: id = Object id to return
	 * @params: type = One of 'competition', 'player', 'match' or 'team' to which the id refers.
	 */ 	
	public function getInjuries($id=null,$type=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
						
		$this->function='get_injuries';
		$this->getXml();
	}

	/*
	 * Returns all matches (and related events) that belong to an area, season, round, group, match or team.
	 * Required:
	 * @params: id = Object id to return
	 * @params: type = One of 'area', 'season', 'round', 'group', 'match', 'team' or 'player' to which the id refers. If area is selected start_date and end_date must be specified and span at most 24 hours. If team is selected they may span at most one year
	 * Optional:
	 * @params: detailed = Whether detailed match descriptions should be returned. Either 'yes' or 'no' (default 'no')
	 * @params: start_date = Selection date, format 'yyyy-mm-dd hh:mm:ss'
	 * @params: end_date = Selection date, format 'yyyy-mm-dd hh:mm:ss'
	 * @params: last_updated = Only get matches last updated after given date/time, format 'yyyy-mm-dd hh:mm:ss'
	 * @params: limit = Limit to the number of matches returned. Has to be used in conjunction with either start_date 
	 * 					to get upcoming matches or end_date to get previous matches.
	 * @params: played = Use when type=player to include matches where the player was in lineup or subbed in
	 */ 	
	public function getMatches($id=null,$type=null,$detailed=null,$start_date=null,$end_date=null,$last_updated=null,$limit=null,$played=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
		if($detailed!==null)
			$this->params.='&detailed='.$detailed;
		if($start_date!==null)
			$this->params.='&start_date='.$start_date;
		if($end_date!==null)
			$this->params.='&end_date='.$end_date;
		if($last_updated!==null)
			$this->params.='&last_updated='.$last_updated;
		if($limit!==null)
			$this->params.='&limit='.$limit;
		if($played!==null)
			$this->params.='&played='.$played;
						
		$this->function='get_matches';
		$this->getXml();
	}

	/*
	 * Returns all live matches (matches covered with livescores) for a given date (defaults to today in CET). 
	 * It contains all events and detailed info by default (like get_matches?detailed=yes call).
	 * Optional:
	 * @params: now_playing = only get current matches, either 'yes' or 'no', defaults to 'no'
	 * @params: date = date to get live matches from, may not be more than three days away or in the past, format 'yyyy-mm-dd', defaults to today
	 * @params: minutes = if you want to get actual game minute and extra minute, this works in a combination with now_playing=yes
	 * @params: detailed = Whether detailed match descriptions should be returned. Either 'yes' or 'no' (default 'yes')
	 * @params: id = Optional id, in combination with type. If not specified, all subscribed live matches are returned
	 * @params: type = Optional id, in combination with type. If not specified, all subscribed live matches are returned
	 */ 	
	public function getMatchesLive($now_playing=null,$date=null,$minutes=null,$detailed=null,$id=null){
		
		$this->params='';
		
		if($now_playing!==null)
			$this->params.='&now_playing='.$now_playing;
		if($date!==null)
			$this->params.='&date='.$date;
		if($minutes!==null)
			$this->params.='&minutes='.$minutes;
		if($detailed!==null)
			$this->params.='&detailed='.$detailed;
		if($id!==null)
			$this->params.='&id='.$id;
						
		$this->function='get_matches_live';
		$this->getXml();
	}

	/*
	 * Returns additional information per match, like details about goals (type of goal, distance to goal), 
	 * bookings (reason for booking) and line-up (position on pitch).
	 * Required:
	 * @params: id = Match id to return
	 * Optional:
	 * @params: last_updated = Only get matches last updated after given date, format 'yyyy-mm-dd hh:mm:ss'
	 */ 	
	public function getMatchExtra($id=null,$last_updated=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($last_updated!==null)
			$this->params.='&last_updated='.$last_updated;
						
		$this->function='get_match_extra';
		$this->getXml();
	}

	/*
	 * Returns the formation of team and player position on pitch per match. Updated only once before the start of game. 
	 * The output can be visualized in a graphical pitch view to place player positions on the field.
	 * Required:
	 * @params: id = Match id to return
	 * Optional:
	 * @params: last_updated = Only get matches last updated after given date, format 'yyyy-mm-dd hh:mm:ss'
	 */ 	
	public function getMatchFormations($id=null,$last_updated=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($last_updated!==null)
			$this->params.='&last_updated='.$last_updated;
						
		$this->function='get_match_formations';
		$this->getXml();
	}

	/*
	 * Returns additional statistics (corners, fouls, etc) for a specific match.
	 * Required:
	 * @params: id = Match id
	 */ 	
	public function getMatchStatistics($id=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
						
		$this->function='get_match_statistics';
		$this->getXml();
	}

	/*
	 * Returns the match events from players playing abroad.
	 * Required:
	 * @params: id = Object id to return
	 * @params: type = It is always area.
	 * @params: date = Date to get activity for.
	 */ 	
	public function getPlayersAbroad($id=null,$type=null,$date=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
		if($date!==null)
			$this->params.='&date='.$date;
						
		$this->function='get_players_abroad';
		$this->getXml();
	}

	/*
	 * Returns the player events (yellow cards, red cards and goal) per round or season.
	 * Required:
	 * @params: id = round or season to get statistics from
	 * @params: type = round or season for only round statistics or season statistics
	 * Optional:
	 * @params: team_id = only select a single team to get statistics from
	 */ 	
	public function getPlayerStatistics($id=null,$type=null,$team_id=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
		if($team_id!==null)
			$this->params.='&team_id='.$team_id;
						
		$this->function='get_player_statistics';
		$this->getXml();
	}

	/*
	 * Returns rankings for a given year and date and given type . If year and date are omitted, the lastest ranking is returned
	 * Required:
	 * @params: type = One of 'fifa'
	 * Optional:
	 * @params: year
	 * @params: month
	 */ 	
	public function getRankings($type=null,$year=null,$month=null){
		
		$this->params='';
		
		if($type!==null)
			$this->params.='&type='.$type;
		if($year!==null)
			$this->params.='&year='.$year;
		if($month!==null)
			$this->params.='&month='.$month;
						
		$this->function='get_rankings';
		$this->getXml();
	}

	/*
	 * Returns referees for given season, round, match or referee
	 * Required:
	 * @params: id
	 * @params: type = One of 'season' or 'round' or 'match_id' or 'referee'
	 */ 	
	public function getReferees($id=null,$type=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
						
		$this->function='get_referees';
		$this->getXml();
	}

	/*
	 * Returns all rounds belonging to a season
	 * Required:
	 * @params: season_id = season to select
	 */ 	
	public function getRounds($season_id=null){
		
		$this->params='';
		
		if($season_id!==null)
			$this->params.='&season_id='.$season_id;
						
		$this->function='get_rounds';
		$this->getXml();
	}

	/*
	 * Returns a list of players linked to an area, season, round, group or team. 
	 * Required:
	 * @params: id = id belonging to the selection type chosen
	 * @params: type = selection type, one of 'area', 'season', 'round', 'group', 'team' or 'player'
	 * Optional:
	 * @params: detailed = show all player details, either 'yes' or 'no'
	 * @params: statistics = show player's statistics from the current season, either 'yes' or 'no'
	 * @params: contracts
	 * @params: last_updated = Only get players added after given date, format 'yyyy-mm-dd hh:mm:ss'
	 * @params: active = Whether only players currently active should be returned. Yes or no, defaults to no. 
	 * 					If no, all players that have played during the last (or selected) season will be returned, not only the ones still active. 
	 * 					If yes, only the players that are active will be returned, whatever season is selected.
	 */ 	
	public function getSquads($id=null,$type=null,$detailed=null,$statistics=null,$last_updated=null,$active=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
		if($detailed!==null)
			$this->params.='&detailed='.$detailed;
		if($statistics!==null)
			$this->params.='&statistics='.$statistics;
		if($last_updated!==null)
			$this->params.='&last_updated='.$last_updated;
		if($active!==null)
			$this->params.='&active='.$active;
						
		$this->function='get_squads';
		$this->getXml();
	}

	/*
	 * Returns all changes in the relationship team - people (incl transfers, loans, retirements, etc.).
	 * Required:
	 * @params: last_updated = Only get memberships last updated after given date, format 'yyyy-mm-dd'
	 */ 	
	public function getSquadsChanges($last_updated=null){
		
		$this->params='';
		
		if($last_updated!==null)
			$this->params.='&last_updated='.$last_updated;
						
		$this->function='get_squads_changes';
		$this->getXml();
	}

	/*
	 * Provides a list of players which are suspended in the particular season, round or for a particular match.
	 * Required:
	 * @params: id = Match id to return
	 * @params: type = 'match' type only
	 */ 	
	public function getSuspensions($id=null,$type=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
						
		$this->function='get_suspensions';
		$this->getXml();
	}

	/*
	 * Returns list of players that will be suspended if they receive one more yellow card.
	 * Required:
	 * @params: id = Object id to return
	 * @params: type = 'match' type only
	 */ 	
	public function getSuspensionsWarning($id=null,$type=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		if($type!==null)
			$this->params.='&type='.$type;
						
		$this->function='get_suspensions_warning';
		$this->getXml();
	}

	/*
	 * Returns the statistics of a team in a specific season.
	 * Required:
	 * @params: team_id = team to compute statistics for
	 * @params: type = selection type, one of 'player', 'team'
	 * Optional:
	 * @params: form = only use form, either 'yes' or 'no', if not set both form and season statistics are computed
	 * @params: season_id = use selected season to compute statistics for, defaults to last season of national league
	 */ 	
	public function getTeamStatistics($team_id=null,$form=null,$season_id=null){
		
		$this->config['lang']=NULL;
		$this->params='';
		
		if($team_id!==null)
			$this->params.='&team_id='.$team_id;
		if($form!==null)
			$this->params.='&form='.$form;
		if($season_id!==null)
			$this->params.='&season_id='.$season_id;
		
						
		$this->function='get_team_statistics';
		$this->getXml();
		$this->config['lang']='ar';
	}

	/*
	 * Returns list of transfers linked to area, competition or person.
	 * Required:
	 * @params: id = id belonging to the selection type chosen
	 * @params: type = selection type, one of 'area', 'competition', 'person' or 'team'
	 * Optional:
	 * @params: start_date = Selection date, format 'yyyy-mm-dd hh:mm:ss'
	 * @params: end_date = Selection date, format 'yyyy-mm-dd hh:mm:ss'
	 * @params: updated_since = Transfers updated after given date (new transfers not included), format 'yyyy-mm-dd hh:mm:ss'
	 * @params: proceeded = Determines if transfer is concluded or will be concluded at a future date based on team_people_transactions.
	 * 						proceeded database column. ?proceeded=yes filters out concluded transfers and ?proceeded=no filters out future transfers.
	 * @params: limit = number of requested rows (default null)
	 * @params: offset = number of first row (default 0)
	 */ 	
	public function getTransfers($id=null,$type=null,$start_date=null,$end_date=null,$updated_since=null,$proceeded=null,$limit=null,$offset=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
		
		if($type!==null)
			$this->params.='&type='.$type;
		
		if($start_date!==null)
			$this->params.='&start_date='.$start_date;
		
		if($end_date!==null)
			$this->params.='&end_date='.$end_date;
		
		if($updated_since!==null)
			$this->params.='&updated_since='.$updated_since;
		
		if($proceeded!==null)
			$this->params.='&proceeded='.$proceeded;
		
		if($limit!==null)
			$this->params.='&limit='.$limit;
		
		if($offset!==null)
			$this->params.='&offset='.$offset;
				
		$this->function='get_transfers';
		$this->getXml();
	}

	/*
	 * Returns trophies for given season, competition or team
	 * Required:
	 * @params: id = id belonging to the selection type chosen
	 * @params: type = One of 'competition' or 'season' or 'team'
	 */ 	
	public function getTrophies($id=null,$type=null){
		
		$this->params='';
		
		if($id!==null)
			$this->params.='&id='.$id;
						
		if($type!==null)
			$this->params.='&type='.$type;
						
		$this->function='get_trophies';
		$this->getXml();
	}

}

?>
