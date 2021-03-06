(function (window, Q, $, undefined) {

var Users = Q.Users;
var Streams = Q.Streams;
var Interests = Streams.Interests;

/**
 * Streams Tools
 * @module Streams-tools
 */
	
/**
 * Tool for user to manage their interests in a community
 * @class Streams interests
 * @constructor
 * @param {Object} [options] This is an object of parameters for this function
 *  @param {String} [options.communityId=Q.info.app] The id of the user representing the community publishing the interests
 *  @param {String} [options.userId=Users.loggedInUserId()] The id of the user whose interests are to be displayed, defaults to the logged-in user
 *  @param {Array} [options.ordering={}] To override what interest categories to show and in what order
 *  @param {Object} [options.expandable={}] Any options to pass to the expandable tools
 *  @param {String} [options.cachebust=1000*60*60*24] How often to reload the list of major community interests
 *  @param {Q.Event} [options.onReady] this event occurs when the tool interface is ready
 */
Q.Tool.define("Streams/interests", function (options) {
	var tool = this;
	var state = tool.state;
	var p = new Q.Pipe();
	var $unlistedTitle;
	var lastVal, lastImage;
	var revealingNewInterest = false;
	var $te = $(tool.element);
	var anotherUser = state.userId;
	if (anotherUser) {
		$te.addClass('Streams_interests_anotherUser');
	}
	
	if (!$te.children().length) {
		$te.html(
			'<div class="Streams_interests_filter">' +
			'<input class="Streams_interests_filter_input" placeholder="What do you enjoy?"></input>' +
			'</div>'
		);
	}

	state.communityId = state.communityId || Q.info.app;
	
	function addExpandable(category, interests) {
		var cn = Q.normalize(category);
		var url = Q.url('img/interests/categories/'+cn+'.png');
		var img = "<img src='"+url+"'>";
		var content = '';
		var count = 0;
		Q.each(interests, function (subcategory, interests) {
			var h3 = subcategory ? "<h3>"+subcategory+"</h3>" : '';
			content += h3 + _listInterests(category, interests);
			count += Object.keys(interests).length;
		});
		var expandableOptions = Q.extend({
			title: img+"<span class='Streams_interests_category_title'>"+category+"</span>",
			content: content,
            count: '',
			category: category
		}, state.expandable);
		var $expandable = $(Q.Tool.setUpElement(
			'div', 'Q/expandable', expandableOptions, 
			tool.prefix + 'Q_expandable_' + Q.normalize(category))
		);
		$expandable.appendTo(tool.element).activate(p.fill(category));
	}

	var src = 'action.php/Streams/interests';
	var criteria = { communityId: state.communityId };
	Q.addScript(Q.url(src, criteria, { cacheBust: state.cacheBust }),
	function () {
		var categories = state.ordering
			= state.ordering || Object.keys(Interests.all[state.communityId]);
		Q.each(state.ordering, function (i, category) {
			addExpandable(
				category, 
				Interests.all[state.communityId][category], 
				{ascending: true}
			);
		});
		var waitFor = categories.concat(anotherUser ? ['my', 'anotherUser'] : ['my']);
		p.add(waitFor, 1, function (params, subjects) {
			tool.$('.Streams_interest_title').removeClass('Q_selected');
			var $jq;
			var otherInterests = {};
			var normalized, expandable;
			var interests = anotherUser ? params.anotherUser[0] : params.my[0];
			var myInterests = params.my[0];
			for (normalized in interests) {
				$jq = $('#Streams_interest_title_' + normalized)
				.addClass('Streams_interests_anotherUser');
				if ($jq.length) {
					if (normalized in myInterests) {
						$jq.addClass('Q_selected');
						expandable = $jq.closest('.Q_expandable_tool')[0].Q('Q/expandable');
						expandable.state.count++;
						expandable.stateChanged(['count']);
					}
				} else {
					otherInterests[normalized] = interests[normalized];
				}
			}
			if (!Q.isEmpty(otherInterests)) {
				for (normalized in otherInterests ) {
					var interestTitle = otherInterests[normalized];
					var parts = interestTitle.split(': ');
					var category = parts[0];
					var title = parts[1];
					var id = 'Q_expandable_'+Q.normalize(category);
					var $expandable = $('#' + tool.prefix + id);
					var $content = $expandable.find('.Q_expandable_content');
					if (!$expandable.length) {
						continue;
					}
					var $other = $expandable.find('.Streams_interests_other');
					if (!$other.length) {
						$other = $('<h3 class="Streams_interests_other">Other</h3>')
							.appendTo($content);
					}
					var id = 'Streams_interest_title_' + normalized;
					var $span = $('<span />', {
						'id': id,
						'data-category': category,
						'class': 'Streams_interest_title'
					}).text(title)
					if (anotherUser) {
						$span.addClass('Streams_interests_anotherUser');
					}
					var $span2 = $('<span class="Streams_interest_sep">, </span>');
					$content.append($span, $span2);
					if (normalized in myInterests) {
						$span.addClass('Q_selected');
						expandable = $expandable[0].Q('Q/expandable');
						expandable.state.count++;
						expandable.stateChanged(['count']);
					}
					Q.setObject([title, id], true, allInterests);
				}
				$('.Q_expandable_content .Streams_interests_other').each(function () {
					$(this).nextAll('.Streams_interest_sep').last().remove(); // the last separator	
				});
			}
			if (anotherUser) {
				$te.find('.Q_expandable_tool').each(function () {
					var $this = $(this);
					if (!$this.find('.Streams_interests_anotherUser').length) {
						$(this).addClass('Streams_interests_anotherUserNone');
					}
				});
				$te.find('h3').each(function () {
					var $this = $(this);
					if (!$this.nextUntil('h3')
					.filter('.Streams_interests_anotherUser').length) {
						$(this).addClass('Streams_interests_anotherUserNone');
					}
				});
				tool.$('.Streams_interest_sep').html(' ');
			}
			state.interests = interests;
			state.otherInterests = otherInterests;
			Q.handle(state.onReady, tool);
		});
		
		var $unlisted1 = $("<div />").html("Don't see it? Try some synonyms.");
		var $unlisted2 = $("<div class='Streams_interest_unlisted1' />")
		.text("If you still can't find what you're looking for, you can add a new interest below:");
		$unlistedTitle = $('<span id="Streams_new_interest_title" />')
			.addClass('Streams_new_interest_title');
		var $select = $('<select class="Streams_new_interest_categories" />')
			.on('change', function () {
				if (!Users.loggedInUser) {
					return;
				}
				var $this = $(this);
				var category = $this.val();
				var interestTitle = category + ': ' + $unlistedTitle.text();
				var normalized = Q.normalize(interestTitle);
				Interests.my[normalized] = interestTitle;
				Interests.add(interestTitle,
				function (err, data) {
					var msg = Q.firstErrorMessage(
						err, data && data.errors
					);
					if (msg) {
						return alert(msg);
					}
					revealingNewInterest = true;
					var parentElement = tool.element.parentNode;
					var toolId = tool.id;
					Q.Tool.remove(tool.element);
					$(Q.Tool.setUpElement('div', 'Streams/interests', toolId))
					.appendTo(parentElement)
					.activate(function () {
						var id = 'Q_expandable_' + Q.normalize(category);
						this.child(id).expand({
							scrollToElement: tool.$('.Streams_interests_other')[0]
						}, function () {
							revealingNewInterest = false;
						});
					});
				}, {
					subscribe: true,
					quiet: false
				});
			});
		var $unlisted = $('<div />')
			.addClass("Streams_interests_unlisted")
			.append($unlisted1, $unlisted2)
			.append(
				$('<div />').append(
					$unlistedTitle.attr('data-category', 'Unlisted')
				)
			).append($select)
			.appendTo(tool.element)
			.hide();
		
		$(tool.element)
		.on(Q.Pointer.fastclick, 'span.Streams_interest_title', function () {
			if (!Users.loggedInUser) {
				return;
			};
			// TODO: ignore spurious clicks that might happen
			// when something is expanding
			var $this = $(this);
			var tool = null;
			var $jq = $this.closest('.Q_expandable_tool');
			if ($jq.length) {
				tool = $jq[0].Q('Q/expandable');
			}
			var title = $this.attr('data-category') + ': ' + $this.text();
			var fields = {
				title: title
			};
			var normalized = Q.normalize(title);
			var change;
			if ($this.hasClass('Q_selected')) {
				change = -1;
				$this.removeClass('Q_selected');
				delete Interests.my[normalized];
				Interests.remove(title, function (err, data) {
					var msg = Q.firstErrorMessage(
						err, data && data.errors
					);
					if (msg) {
						$this.addClass('Q_selected');
					}
				});
			} else {
				change = 1;
				$this.addClass('Q_selected');
				Interests.my[normalized] = $this.text();
				Interests.add(title, function (err, data) {
					var msg = Q.firstErrorMessage(
						err, data && data.errors
					);
					if (msg) {
						$this.removeClass('Q_selected');
					}
				}, {subscribe: true});
			}
			if (tool) {
				tool.state.count = (tool.state.count || 0) + change;
				if (tool.state.count == 0) {
					tool.state.count = '';
				}
				tool.stateChanged(['count']);
			}
		});
		
		var possibleEvents = 'keyup.Streams'
			+ ' blur.Streams'
			+ ' update.Streams'
			+ ' paste.Streams'
			+ ' filter'
			+ ' Q_refresh';
		tool.$('.Streams_interests_filter_input')
		.on(possibleEvents, Q.debounce(function (evt) {
			var $this = $(this);
			if (evt.keyCode === 27) {
				$this.val('');
			}
			var val = $this.val().toLowerCase();
			var len = val.length;
			var existing = {};
			var image = val ? 'clear' : 'filter';
			if (image != lastImage) {
				var src = Q.url('plugins/Q/img/white/'+image+'.png');
				$this.css({
					'background-image': 'url('+src+')',
					'background-position': '100% 50%',
					'background-repeat': 'no-repeat'
				});
				lastImage = image;
			}
			if (val) {
				var showElements = [];
				tool.$('.Streams_interest_sep').html(' ');
				Q.each(allInterests, function (interest, ids) {
					for (var id in ids) {
						var $span = $('#'+id);
						if (!$span.length) continue;
						var matched = false;
						var parts1 = val.split(' ');
						var parts2 = interest.split(' ');
						var pl1 = parts1.length;
						var pl2 = parts2.length;
						for (var i1=0; i1<pl1; ++i1) {
							matched = false;
							for (var i2=0; i2<pl2; ++i2) {
								var p1 = parts1[i1];
								var p2 = parts2[i2].substr(0, p1.length).toLowerCase();
								if (p1 === p2) {
									matched = true;
									break;
								}
							}
							if (matched === false) {
								break;
							}
						}
						if (matched) {
							$span.show();
							var $expandable = $span.closest('.Q_expandable_tool');
							var $h3 = $span.prevAll('h3');
							showElements.push($expandable[0]);
							showElements.push($h3[0]);
							!$expandable.is(":visible") && $expandable.show();
							!$h3.is("visible") && $h3.show();
							$expandable[0].Q("Q/expandable").expand({
								autoCollapseSiblings: false,
								scrollContainer: false
							});
							existing[$span.data('category')] = interest;
						} else {
							$span.hide();
						}
					}
				});
				tool.$('.Q_expandable_tool')
				.add(tool.$('.Q_expandable_tool h3'))
				.each(function () {
					if (showElements.indexOf(this) < 0) {
						$(this).is(":visible") && $(this).hide();
					}
				});
				
				var count = 0;
				$select.empty();
				Q.each(Interests.all[state.communityId], function (category) {
					if (existing[category]
					&& Q.normalize(existing[category]) === Q.normalize(val)) {
						return;
					}
					$('<option />', { value: category })
					.html(category)
					.appendTo($select);
					++count;
				});
				if (count) {
					$unlistedTitle.text(val.toCapitalized());
					$('<option value="" selected="selected" disabled="disabled" />')
						.html('Add under category...')
						.prependTo($select);
					$unlisted.show();
				} else {
					$unlisted.hide();
				}
			} else if (lastVal) {
				if (!revealingNewInterest) {
					tool.$('.Q_expandable_tool').show().each(function () {
						this.Q("Q/expandable").collapse();
					});
				}
				tool.$('.Q_expandable_tool h3').show();
				tool.$('.Streams_interest_sep').html(', ');
				tool.$('.Q_expandable_content span').show();
				$unlisted.hide();
			}
		
			lastVal = val;
		}, 100))
		.on(Q.Pointer.fastclick, function (evt) {
			var $this = $(this);
			var xMax = $this.offset().left + $this.outerWidth(true) -
				parseInt($this.css('margin-right'));
			var xMin = xMax - parseInt($this.css('padding-right'));
			var x = Q.Pointer.getX(evt);
			if (xMin < x && x < xMax) {
				$this.val('').trigger('Q_refresh');
			}
		});
	});
	
	if (Users.loggedInUser) {
		Interests.forMe(state.communityId, function (err, interests) {
			if (err) {
				return alert(Q.firstErrorMessage(err));
			} 
			p.fill('my')(interests);
		});
	} else {
		p.fill('my')({});
	}
	
	if (anotherUser) {
		Interests.forUser(state.userId, state.communityId, function (err, interests) {
			if (err) {
				return alert(Q.firstErrorMessage(err));
			}
			p.fill('anotherUser')(interests);
		});
	}
},

{
	communityId: null,
	expandable: {},
	cacheBust: 1000*60*60*24,
	ordering: null,
	onReady: new Q.Event()
}

);

var allInterests = {};

function _listInterests(category, interests) {
	var lines = [];
	for (var interest in interests) {
		var normalized = Q.normalize(category + ": " + interest);
		var id = 'Streams_interest_title_' + normalized;
		lines.push(
			'<span class="Streams_interest_title" id="'+id
			+ '" data-category="' + category + '">'
			+ interest 
			+ '</span>'
		);
		Q.setObject([interest, id], true, allInterests);
	}
	return lines.join('<span class="Streams_interest_sep">, </span>');
}

Q.Template.set('Streams/interests', 
'{{#if filter}}'
+ '<div class="Streams_interests_filter">'
	+ '<input class="Streams_interests_filter_input" placeholder="What do you enjoy?"></input>'
+ '</div>'
+ '{{/filter}}'
+ '<div class="Streams_interests_all"></div>'
);

})(window, Q, jQuery);