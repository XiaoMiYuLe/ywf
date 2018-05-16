var CodeMirror_Helper = function(id, obj_cm) {

	obj_cm = CodeMirror.fromTextArea(document.getElementById(id), {
		lineNumbers: true,
		styleActiveLine: true,
		matchBrackets: true,
		theme: "default",
		onKeyEvent: function(i, e) {
			// Hook into ctrl-space
			if (e.keyCode == 32 && (e.ctrlKey || e.metaKey) && !e.altKey) {
				e.stop();
				return startComplete();
			}
		},
		extraKeys: {
			"F11": function(cm) {
				cm.setOption("fullScreen", !cm.getOption("fullScreen"));
			},
			"Esc": function(cm) {
				if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
			}
		}
	});
	
	return obj_cm;
};
