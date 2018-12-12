<script language="Javascript">
	// Redirect to Futbol Responsive Version
	$.get(
		"/futbolm/users/ismobile"
		, {}
		, function (json) {
				$.each(json, function(i, ismobile){
					if (ismobile){
						if (confirm("Desear ver la version para dispositivos moviles de Futbolista?")){
							document.location="/futbolm/users/login";
						}
					}
				});
		}
		, "json"
	);
</script>
