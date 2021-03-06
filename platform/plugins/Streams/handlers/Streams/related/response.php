<?php

function Streams_related_response()
{
	if (!Q_Request::slotName('relations') and !Q_Request::slotName('streams')) {
		return;
	}
	
	$user = Users::loggedInUser();
	$asUserId = $user ? $user->id : '';
	$publisherId = Streams::requestedPublisherId(true);
	$streamName = Streams::requestedName(true, 'original');
	$isCategory = !(empty($_REQUEST['isCategory']) or strtolower($_REQUEST['isCategory']) === 'false');
	$slotNames = Q_Request::slotNames();
	$streams_requested = in_array('relatedStreams', $slotNames);
	$options = Q::take($_REQUEST, array('limit', 'offset', 'min', 'max', 'type', 'prefix'));
	$options['relationsOnly'] = !$streams_requested;
	$options['orderBy'] = !empty($_REQUEST['ascending']);
	$options['fetchOptions'] = array('withParticipant' => true);
	$result = Streams::related(
		$asUserId,
		$publisherId,
		$streamName,
		$isCategory,
		$options
	);

	$fields = Q::ifset($_REQUEST, 'fields', null);
	$exportOptions = array('numeric' => true);
	if (isset($fields)) {
		if (is_string($fields)) {
			$fields = array_map('trim', explode(',', $fields));
		}
		$exportOptions['fields'] = $fields;
	}
	if ($streams_requested) {
		$rel = Db::exportArray($result[0], $exportOptions);
	} else {
		$rel = Db::exportArray($result, $exportOptions);
	}

	if (!empty($_REQUEST['omitRedundantInfo'])) {
		if ($isCategory) {
			foreach ($rel as &$r) {
				unset($r['toPublisherId']);
				unset($r['toStreamName']);
			}
		} else {
			foreach ($rel as &$r) {
				unset($r['fromPublisherId']);
				unset($r['fromStreamName']);
			}
		}
	}

	Q_Response::setSlot('relations', $rel);
	if (!$streams_requested) {
		return;
	}

	$streams = $result[1];
	$arr = Db::exportArray($streams, array('numeric' => true));
	foreach ($arr as $k => $stream) {
		if (!$stream) continue;
		$s = $streams[$stream['name']];
		$arr[$k]['access'] = array(
			'readLevel' => $s->get('readLevel', $s->readLevel),
			'writeLevel' => $s->get('writeLevel', $s->writeLevel),
			'adminLevel' => $s->get('adminLevel', $s->adminLevel)
		);
	}
	Q_Response::setSlot('relatedStreams', $arr);
	
	$stream = $result[2];
	if (is_array($stream)) {
		Q_Response::setSlot('streams', Db::exportArray($stream));
		return;
	} else if (is_object($stream)) {
		Q_Response::setSlot('stream', $stream->exportArray());
	} else {
		Q_Response::setSlot('stream', false);
	}
	
	if (!empty($_REQUEST['messages'])) {
		$max = -1;
		$limit = $_REQUEST['messages'];
		$messages = false;
		$type = isset($_REQUEST['messageType']) ? $_REQUEST['messageType'] : null;
		if ($stream->testReadLevel('messages')) {
			$messages = Db::exportArray($stream->getMessages(
				compact('type', 'max', 'limit')
			));
		}
		Q_Response::setSlot('messages', $messages);
	}
	if (!empty($_REQUEST['participants'])) {
		$limit = $_REQUEST['participants'];
		$offset = -1;
		$participants = false;
		if ($stream->testReadLevel('participants')) {
			$participants = Db::exportArray($stream->getParticipants(
				compact('limit', 'offset')
			));
		}
		Q_Response::setSlot('participants', $participants);
	}
}
