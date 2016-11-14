/*
 * jQuery DP Calendar v2.3.4
 *
 * Copyright 2011, Diego Pereyra
 *
 * @Web: http://www.dpereyra.com
 * @Email: info@dpereyra.com
 *
 * Depends:
 *	jquery.ui.core.js
 *	jquery.ui.datepicker.js
 */

(function ($) {
	$.fn.dp_calendar = function (options) {	
	
		
		/* Setting vars*/
		var opts, events_array, date_selected, order_by, format_ampm, show_datepicker, show_time, link_color, show_priorities, show_sort_by, onChangeMonth, onChangeDay, onClickMonthName, onClickEvents, DP_LBL_NO_ROWS, DP_LBL_SORT_BY, DP_LBL_TIME, DP_LBL_TITLE, DP_LBL_PRIORITY, div_main_date, main_date, prev_month, toggleDP, next_month, div_dates, list_days, clear, div_clear, day_name, calendar_list, h2_sort_by, cl_sort_by, li_time, li_title, li_priority, ul_list, $dp, curr_day, curr_day_name, curr_date, curr_month_name_short, curr_month, curr_month_name, curr_year, ul_list_days, added_events, recurring_frecuency_active = false;
		
		opts = $.extend({}, $.fn.dp_calendar.defaults, options);
		
		events_array = opts.events_array;
		date_selected = opts.date_selected;
		order_by = opts.order_by;
		format_ampm = opts.format_ampm;
		show_datepicker = opts.show_datepicker;
		link_color = opts.link_color;
		show_priorities = opts.show_priorities;
		show_sort_by = opts.show_sort_by;
		show_time = opts.show_time;
		onChangeMonth = opts.onChangeMonth;
		onChangeDay = opts.onChangeDay;
		onClickMonthName = opts.onClickMonthName;
		onClickEvents = opts.onClickEvents;
		date_range_start = opts.date_range_start;
		date_range_end = opts.date_range_end;
		
		/* Labels & Messages*/
		DP_LBL_EVENTS = $.fn.dp_calendar.regional['']['DP_LBL_EVENTS'];
		DP_LBL_NO_ROWS = $.fn.dp_calendar.regional['']['DP_LBL_NO_ROWS'];
		DP_LBL_SORT_BY = $.fn.dp_calendar.regional['']['DP_LBL_SORT_BY'];
		DP_LBL_TIME = $.fn.dp_calendar.regional['']['DP_LBL_TIME'];
		DP_LBL_TITLE = $.fn.dp_calendar.regional['']['DP_LBL_TITLE'];
		DP_LBL_PRIORITY = $.fn.dp_calendar.regional['']['DP_LBL_PRIORITY'];
		
		/* Padding function */
		function dp_str_pad(input, pad_length, pad_string, pad_type) {
			var half = '',
				pad_to_go,
				str_pad_repeater;
		 
			str_pad_repeater = function (s, len) {
				var collect = '',
					i;
		 
				while (collect.length < len) {
					collect += s;
				}
				collect = collect.substr(0, len);
		 
				return collect;
			};
		 
			input += '';
			pad_string = pad_string !== undefined ? pad_string : ' ';
		 
			if (pad_type !== 'STR_PAD_LEFT' && pad_type !== 'STR_PAD_RIGHT' && pad_type !== 'STR_PAD_BOTH') {
				pad_type = 'STR_PAD_RIGHT';
			}
			if ((pad_to_go = pad_length - input.length) > 0) {
				if (pad_type === 'STR_PAD_LEFT') {
					input = str_pad_repeater(pad_string, pad_to_go) + input;
				} else if (pad_type === 'STR_PAD_RIGHT') {
					input = input + str_pad_repeater(pad_string, pad_to_go);
				} else if (pad_type === 'STR_PAD_BOTH') {
					half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
					input = half + input + half;
					input = input.substr(0, pad_length);
				}
			}
		 
			return input;
		}
		
		/* in_array function */
		function dp_in_array (needle, haystack, argStrict) {
			var key = '',
				strict = !! argStrict;
		 
			if (strict) {
				for (key in haystack) {
					if (haystack[key] === needle) {
						return true;
					}
				}
			} else {
				for (key in haystack) {
					if (haystack[key] == needle) {
						return true;
					}
				}
			}
		 
			return false;
		}
				
		/* calculeDates() Core function */
		function calculeDates() {
			/* Setting vars */
			var newLI, newText, i;

			
			curr_day = date_selected.getDay();
			curr_day_name = $.fn.dp_calendar.regional[""].dayNames[curr_day];
			curr_date = date_selected.getDate();
			curr_month = date_selected.getMonth();
			curr_month_name = $.fn.dp_calendar.regional[""].monthNames[curr_month];
			curr_month_name_short = $.fn.dp_calendar.regional[""].monthNamesShort[curr_month];
			curr_year = date_selected.getFullYear();
			
			//Set defaults options
			$.fn.dp_calendar.date_selected = date_selected;
			$.fn.dp_calendar.order_by = order_by;
			$.fn.dp_calendar.format_ampm = format_ampm;
			$.fn.dp_calendar.curr_day = curr_day;
			$.fn.dp_calendar.curr_day_name = curr_day_name;
			$.fn.dp_calendar.curr_date = curr_date;
			$.fn.dp_calendar.curr_month = curr_month;
			$.fn.dp_calendar.curr_month_name = curr_month_name;
			$.fn.dp_calendar.curr_month_name_short = curr_month_name_short;
			$.fn.dp_calendar.curr_year = curr_year;
			
			/* Clean the list of days */
			//while (ul_list_days.firstChild) { ul_list_days.removeChild(ul_list_days.firstChild); }
			$(ul_list_days).html("");
			
			if(order_by === 1) {
				events_array.sort(function(a,b) {
				
					if (a["sectioncss"] == null){
						ax = Date.UTC(0, 0, 0, a["startDate"].getHours(), a["startDate"].getMinutes());
						bx = Date.UTC(0, 0, 0, b["startDate"].getHours(), b["startDate"].getMinutes());
						return ax == bx ? 0 : (ax < bx ? -1 : 1)
					}
					else{
						a = a["sectioncss"];
						b = b["sectioncss"];
						return a == b ? 0 : (a < b ? -1 : 1)
				
					}
				});
			}
			if(order_by === 2) {
				events_array.sort(function(a,b) {
					a = a["title"].toLowerCase();
					b = b["title"].toLowerCase();
					
					return a == b ? 0 : (a < b ? -1 : 1)
				});
				
			}
			if(order_by === 3) {
				events_array.sort(function(a,b) {
					a = a["priority"];
					b = b["priority"];
					return a == b ? 0 : (a > b ? -1 : 1)
				});
				
			}
			
			/* Update the list of days*/
			for (i = 1; i <= new Date(curr_year, (curr_month + 1), 0).getDate(); i++) {
				newLI = $('<li />');
				
				if (curr_date === i) {
					newLI.addClass("active");
				}
				newText = document.createTextNode(dp_str_pad(i, 2, "0", "STR_PAD_LEFT"));
				
				$(newLI).html(newText).attr('id', 'dpEventsCalendar_li_'+Date.UTC(curr_year, curr_month, i));
				$(ul_list_days).append(newLI);
			}
			
			jQuery($(div_dates).find("li")).css("color", link_color);

			/* Check Date Range */
			if(date_range_start != null) {
				if(date_range_start.getMonth() == curr_month) {
					jQuery($(div_dates).find("li:lt("+(date_range_start.getDate() - 1)+")")).addClass("dp_calendar_edisabled");
					$(prev_month).hide();
				} else {
					$(prev_month).show();
				}
				$dp.datepicker("option", {minDate: date_range_start});
			}
			
			if(date_range_end != null) {
				if(date_range_end.getMonth() == curr_month) {
					jQuery($(div_dates).find("li:gt("+(date_range_end.getDate() - 1)+")")).addClass("dp_calendar_edisabled");
					$(next_month).hide();
				} else {
					$(next_month).show();
				}
				$dp.datepicker("option", {maxDate: date_range_end});
			}
			
			
			/* Onclick Days Event*/
			$($(ul_list_days).find("li:not(.dp_calendar_edisabled)")).click(function (e) {
	
				date_selected = new Date(curr_year, curr_month, $(this).html());
                            
				$($(ul_list_days).find("li:not(.dp_calendar_edisabled)")).each(function (i) {
					this.className = "";	
				});
				this.className = "active";	
				calculeDates();	
				onChangeDay(date_selected);
			});
			
			/* Days and Months Labels*/
			$(day_name).html("");
			$(day_name).append("<h1>" + curr_day_name + "&nbsp;<span class='span_day'>" + dp_str_pad(curr_date, 2, "0", "STR_PAD_LEFT") + "</span><span class='span_month'>/" + curr_month_name_short + "</span></h1>");		
			   
			$dp.datepicker("setDate", date_selected);
			$(toggleDP).html(curr_month_name + " " + curr_year);
			
			/* Preloader Message */
			$(ul_list).html("<div class='loading'></div>");
			
//		
			
		}
		
		
		/* CREATING THE HTML CODE */
		
		this.addClass("dp_calendar");
		this.html("");
		
		div_main_date = $('<div />').addClass('div_main_date');
		
		main_date = $('<div />').addClass('main_date');

		$(div_main_date).append(main_date);
		
		prev_month = $('<a />').attr({href : 'javascript:void(0);', id: 'prev_month'}).html('&laquo;');

		
		toggleDP = $('<a />').attr({href : 'javascript:void(0);', id: 'toggleDP'});
		
		next_month = $('<a />').attr({href : 'javascript:void(0);', id: 'next_month'}).html('&raquo;');

		
		$(main_date).append(prev_month);
		$(main_date).append(toggleDP);
		$(main_date).append(next_month);
		this.append(div_main_date);
		
		div_dates = $('<div />').addClass('div_dates');
		
		list_days = $('<ul />').attr('id', 'list_days');
		ul_list_days = list_days;
		
		clear = $('<div />').addClass('clear');
		
		day_name = $('<div />').addClass('day_name').attr('id', 'day_name');
		
		$(div_dates).append(ul_list_days);
		div_clear = $('<div />').addClass('clear');
		$(div_dates).append(div_clear);
		$(div_dates).append(day_name);
		div_clear = $('<div />').addClass('clear');
		$(div_dates).append(div_clear);
		this.append(div_dates);
		


		
		
		$dp = $("<input type='text' />").hide().datepicker({
			onSelect: function (dateText, inst) {
			    date_selected = new Date(dateText);
			    calculeDates();
			}
		}).appendTo('body');
		
		
		$(toggleDP).click(function (e) {
			if (show_datepicker === true) {
				if ($dp.datepicker('widget').is(':hidden')) {
					$dp.datepicker("show");
					$dp.datepicker("widget").position({
						my: "top",
						at: "top",
						of: this
					});
				} else {
					$dp.hide();
				}
				
			}
			onClickMonthName();
		
			e.preventDefault();
		});	
		
		
		calculeDates();
		
		$(next_month).click(function (e) {
			date_selected = date_selected.add(1).month();
			calculeDates();
			onChangeMonth();
		});
		
		$(prev_month).click(function (e) {
			date_selected = date_selected.add(-1).month();
			calculeDates();
			onChangeMonth();
		});
		
		$($(cl_sort_by).find("li")).click(function (e) {
			$($(cl_sort_by).find("li")).each(function (i) {
				this.className = "";
			});
			this.className = "active";
			$($(cl_sort_by).find("li")).each(function (i) {
				if (this.className === "active") { order_by = (i + 1); }
			});
			calculeDates();
		});
		
		
	};	
	
	/* Default Parameters and Events */
	$.fn.dp_calendar.defaults = {  
		events_array: new Array(),
		date_selected: new Date(),
		order_by: 1,
		show_datepicker: true,
		show_priorities: true,
		show_sort_by: true,
		show_time: true,
		format_ampm: false,
		onChangeMonth: function () {},
		onChangeDay: function () {},
		onClickMonthName: function () {},
		onClickEvents: function () {},	
		link_color: '#929292',
		date_range_start: null,
		date_range_end: null
	};
	
	/* Global parameters */
	$.fn.dp_calendar.date_selected = $.fn.dp_calendar.defaults.date_selected;
	$.fn.dp_calendar.order_by = $.fn.dp_calendar.defaults.order_by;
	$.fn.dp_calendar.format_ampm = $.fn.dp_calendar.defaults.format_ampm;
	$.fn.dp_calendar.curr_day = "";
	$.fn.dp_calendar.curr_day_name = "";
	$.fn.dp_calendar.curr_date = "";
	$.fn.dp_calendar.curr_month = "";
	$.fn.dp_calendar.curr_month_name = "";
	$.fn.dp_calendar.curr_month_name_short = "";
	$.fn.dp_calendar.curr_year = "";
	$.fn.dp_calendar.regional = [];
	$.fn.dp_calendar.regional[''] = {
		closeText: 'Đóng',
		prevText: 'Sau',
		nextText: 'Tiếp',
		currentText: 'Today',
		monthNames: ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6',
		'Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'],
		monthNamesShort: ['1', '2', '3', '4', '5', '6',
		'7', '8', '9', '10', '11', '12'],
		dayNames: ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'],
		dayNamesShort: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
		dayNamesMin: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
		DP_LBL_EVENTS: 'Events',
		DP_LBL_NO_ROWS: 'No results were found in this date.',
		DP_LBL_SORT_BY: 'SORT BY:',
		DP_LBL_TIME: 'TIME',
		DP_LBL_TITLE: 'TITLE',
		DP_LBL_PRIORITY: 'PRIORITY'};
	
	
	/* setDate(date) Method */
	$.fn.dp_calendar.setDate = function (date) {
		$.fn.dp_calendar({
			date_selected: date
		});
	};
	
	/* setDay(day) Method */
	$.fn.dp_calendar.setDay = function (day) {
		$.fn.dp_calendar({
			date_selected: new Date($.fn.dp_calendar.curr_year, $.fn.dp_calendar.curr_month, day)
		});
	};
	
	/* setMonth(month) Method */
	$.fn.dp_calendar.setMonth = function (month) {
		$.fn.dp_calendar({
			date_selected: new Date($.fn.dp_calendar.curr_year, month, $.fn.dp_calendar.curr_date)
		});
	};
	
	/* setYear(year) Method */
	$.fn.dp_calendar.setYear = function (year) {
		$.fn.dp_calendar({
			date_selected: new Date(year, $.fn.dp_calendar.curr_month, $.fn.dp_calendar.curr_date)
		});
	};
	
	/* getDate() Method */
	$.fn.dp_calendar.getDate = function () {
		return $.fn.dp_calendar.date_selected;
	};
	
	/* getDay() Method */
	$.fn.dp_calendar.getDay = function () {
		return $.fn.dp_calendar.curr_date;
	};
	
	/* getMonth() Method */
	$.fn.dp_calendar.getMonth = function () {
		return $.fn.dp_calendar.curr_month;
	};
	
	/* getYear() Method */
	$.fn.dp_calendar.getYear = function () {
		return $.fn.dp_calendar.curr_year;
	};

})(jQuery);
