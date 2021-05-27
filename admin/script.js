const simple = {
	table: function(data,tbody) {
		// console.log(data,tbody);
		var thead = $(tbody).closest('table').find('thead th');
		// console.log(thead);
		$(tbody).empty();
		var b = '';
		for (var a in data.data) {
			a = data.data[a];
			console.log(a);
			b += '<tr id="'+a.id+'">';
			$(thead).each(function() {
				for (var c in a) {
					if (c==$(this).attr('data')) {
						b += '<td>'+a[c]+'</td>';
						console.log(c);
					}
				}
			});
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
				console.log(data,typeof data.form);
				if (typeof data.form === 'object') {
					// location.assign(data.form.location);
				}	else {
					$('.modal').show();
					simple.form(data,$('.modal-content form'));
				}
			});
		});
	},
	form: function(data,self) {
		$(self).find('[data]').off();
		$(self).find('[data]').click(function() {
			event.preventDefault();
			var _data = {
				id: data.id,
				data: {}
			};
			var a = $(self).closest('form')[0].elements;
			for (var i=0;i<a.length;i++) {
				if (a[i].name)
					_data.data[ a[i].name ]=a[i].value;
			}
			console.log(_data);
			if ($(this).attr('data')=='save') {
				fetch(window.location.href,{
					method: 'PUT',
					mode: 'cors',
					cache: 'no-cache',
					body: JSON.stringify(_data),
				})
				.then(response => response.json())
				.then(data => {
					console.log('put',data);
					var thead = $('table').find('thead th');
					console.log(thead);
					var tr = $('tr[id="'+data.id+'"] td');
					$(thead).each(function(i) {
						for (var c in data.data) {
							if (c==$(this).attr('data')) {
								console.log(c);
								$(tr[i]).html(data.data[c]);
							}
						}
					});
					$(self).closest('.modal').hide();
				})
				.catch((error) => {
					console.error('Error:', error);
				});
			}	else {
				$(self).closest('.modal').hide();
			}
		});
		$(self).find('main').empty().append( data.form );
	},
	add: function() {

	}
};

$(function() {
	$('a[href="#add"]').click(function() {
		event.preventDefault();
		simple
	});
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
