var edit = {
	toolbar: null,
	keys: {
		p: {
			fn: 'formatBlock',
			key: 'P',
			tag: 'p',
		},
		h1: {
			fn: 'formatBlock',
			key: '1',
			title: 'h1',
			tag: 'h1'
		},
		h2: {
			fn: 'formatBlock',
			key: '2',
			title: 'h2',
			tag: 'h2'
		},
		bold: {
			fn: 'bold',
			key: 'B',
			tag: 'b'
		},
		strong: {
			fn: 'bold',
			key: 'B'
		},
		italic: {
			fn: 'italic',
			key: 'I',
			tag: 'i'
		},
		em: {
			fn: 'italic',
			key: 'I'
		},
		del: {
			fn: 'strikethrough',
			key: '',
		},
	},
	init: function() {
		$('main').before( '<div class="toolbar">'+
			'<span data="">H1</span>'+
			'<span data="">B</span>'+
			'<span data="">I</span>'+
			'</div>' );

		edit.toolbar = $('.toolbar');

		var __keys = Object.keys(edit.keys);
		edit._keys = {};
		for (var i=0;i<__keys.length;i++) {
			// console.log(__keys[i],edit.keys[__keys[i]]);
			var c = edit.keys[__keys[i]];
			if (undefined != c.key) {
				edit._keys[ c.key ]=c;
			}
		}
		console.log(edit._keys);

		$('main')
		.focus()
		.on('keydown',function(e) {
			const caret = toggleTooltip(event, this);
			var keyCode = e.which;
			// console.log(event);
			var keyChar = e.key.toUpperCase();

			if ((e.ctrlKey || e.metaKey) && !e.altKey) {
				ctrl = true;
				// execCmd('execCmd','');
				// console.log( keyCode, keyChar, edit._keys[keyChar] );

				if (undefined != edit._keys[keyChar]) {
					console.log( keyCode, edit._keys[keyChar], caret );
					if (keyCode >= 49 && keyCode <= 49+5) {
						var p = caret.selection.anchorNode.parentNode;
						var h = 'H'+(keyCode-48);
						console.log(p.nodeName);
						if (h != p.nodeName) {
							caret.
							p.outerHTML = '<'+h+'>'+p.innerHTML+'</'+h+'>';
							console.log(caret.selection.anchorOffset);
						}
						// p.nodeName = edit._keys[keyChar].tag.toUpperCase();
					}
					// document.execCommand( edit._keys[keyChar].fn ); 
					event.preventDefault();
				}
			}
		})
		.on('keyup',function(e) {
			// const caret = toggleTooltip(event, this);
			event.preventDefault();

			var keyCode = e.which;

			if (keyCode >= 37 && keyCode <= 40) {
				return;
			}

			if (keyCode === 8 || keyCode === 13 || keyCode === 46) {
				return;
			}

			// console.log( caret );			
			// execCommand
		});

		console.log(page);
	},
	edit: function() {

	},
	save: function() {
		console.log(page);
	}
};

edit.init();

function getCaretCoordinates() {
	let x = 0,
	  y = 0;
	const isSupported = typeof window.getSelection !== "undefined";
	if (isSupported) {
	  const selection = window.getSelection();
	  // Check if there is a selection (i.e. cursor in place)
	  if (selection.rangeCount !== 0) {
		// Clone the range
		const range = selection.getRangeAt(0).cloneRange();
		// Collapse the range to the start, so there are not multiple chars selected
		range.collapse(true);
		// getCientRects returns all the positioning information we need
		const rect = range.getClientRects()[0];
		if (rect) {
		  x = rect.left; // since the caret is only 1px wide, left == right
		  y = rect.top; // top edge of the caret
		}
		// console.log( $(selection.anchorNode).closest('p') );
	  }
	  return { x, y, selection };
	}
	return { x, y };
  }
  
  function getCaretIndex(element) {
	let position = 0;
	const isSupported = typeof window.getSelection !== "undefined";
	if (isSupported) {
	  const selection = window.getSelection();
	  // Check if there is a selection (i.e. cursor in place)
	  if (selection.rangeCount !== 0) {
		// Store the original range
		const range = window.getSelection().getRangeAt(0);
		// Clone the range
		const preCaretRange = range.cloneRange();
		// Select all textual contents from the contenteditable element
		preCaretRange.selectNodeContents(element);
		// And set the range end to the original clicked position
		preCaretRange.setEnd(range.endContainer, range.endOffset);
		// Return the text length from contenteditable start to the range end
		position = preCaretRange.toString().length;
	  }
	}
	return { position };
  }
  
  function toggleTooltip(event, contenteditable) {
	const toolbar = edit.toolbar[0];
	// console.log(toolbar,contenteditable);
	// const tooltip = document.getElementById("tooltip");
	// if (contenteditable.contains(event.target)) {
	  const { x, y, selection } = getCaretCoordinates();
	//   console.log(event.target,selection);
	  // tooltip.setAttribute("aria-hidden", "false");
	  toolbar.setAttribute(
		"style",
		`display: block; top: ${y - 36}px`
		// `display: block; left: ${x - 32}px; top: ${y - 36}px`
	  );
	return { x, y, selection };
  }
  
  function updateIndex(event, element) {
	const textPosition = document.getElementById("caretIndex");
	if (element.contains(event.target)) {
	  textPosition.innerText = getCaretIndex(element).toString();
	} else {
	  textPosition.innerText = "–";
	}
  }
  
  