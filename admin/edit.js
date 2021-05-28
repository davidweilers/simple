var edit = {
	toolbar: null,
	init: function() {
		const editable = document.getElementsByName("main");//contenteditable");
		document.addEventListener("click", (e) => toggleTooltip(e, editable));
		document.addEventListener("click", (e) => updateIndex(e, editable));
		document.addEventListener("keyup", (e) => toggleTooltip(e, editable));
		document.addEventListener("keyup", (e) => updateIndex(e, editable));

		$('main').before( '<div class="toolbar">test</div>' );

		edit.toolbar = $('.toolbar');

		$('main').on('keyup mouseup',function() {
			console.log(  );
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
	  }
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
	return position;
  }
  
  function toggleTooltip(event, contenteditable) {
	const toolbar = edit.toolbar[0];
	console.log(toolbar);
	// const tooltip = document.getElementById("tooltip");
	if (contenteditable.contains(event.target)) {
	  const { x, y } = getCaretCoordinates();
	  // tooltip.setAttribute("aria-hidden", "false");
	  tooltip.setAttribute(
		"style",
		`display: inline-block; left: ${x - 32}px; top: ${y - 36}px`
	  );
	} else {
	  // tooltip.setAttribute("aria-hidden", "true");
	  tooltip.setAttribute("style", "display: none;");
	}
  }
  
  function updateIndex(event, element) {
	const textPosition = document.getElementById("caretIndex");
	if (element.contains(event.target)) {
	  textPosition.innerText = getCaretIndex(element).toString();
	} else {
	  textPosition.innerText = "–";
	}
  }
  
  