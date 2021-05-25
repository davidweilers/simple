const simple = {
	table: function(data,tbody) {
		// console.log(data,tbody);
		var thead = $(tbody).closest('table').find('thead th');
		// console.log(thead);
		$(tbody).empty();
		var b = '';
		for (var a in data.data) {
			a = data.data[a];
			// console.log(a);
			b += '<tr id="'+a.id+'">';
			for (var c in a) {
				console.log(c);
				$(thead).each(function() {
					if (c==$(this).attr('data')) 
						b += '<td>'+a[c]+'</td>';
				});
			}
			b += '</tr>';
		}
		$(tbody).html(b);
		$(tbody).children('tr').click(function() {
			var self = this;
			var data = {
				id: $(this).attr('id')
			};
			fetch(window.location.href,{
				method: 'GETID',
				mode: 'cors',
				cache: 'no-cache',
				body: JSON.stringify(data),
			}).then(response => response.json()).then(data => {
				console.log(data);
				$('.modal').show();
				simple.form(data,$('.modal-content form'));
			});
		});
	},
	form: function(data,self) {
		$(self).find('[data]').click(function() {
			event.preventDefault();
			if ($(this).attr('data')=='save') {

			}	else {
				$(self).closest('.modal').hide();
			}
		});
		$(self).find('main').empty().append( data.form );
	}
};

$(function() {
	$('table[name] tbody').each(function() {
		var self = this;
		fetch(window.location.href,{
			method: 'POST',
			mode: 'cors',
			cache: 'no-cache'
		}).then(response => response.json()).then(data => {
			console.log(data);
			simple.table(data,self);
		});
	});
});
