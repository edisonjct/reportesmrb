$('#busqueda').bootstrapValidator({
	 message: 'Este valor no es valido',
	 feedbackIcons: {
		 valid: 'glyphicon glyphicon-ok',
		 invalid: 'glyphicon glyphicon-remove',
		 validating: 'glyphicon glyphicon-refresh'
	 },
	 fields: {
		 desde: {
			 validators: {
				 notEmpty: {
					 message: 'Selecione fecha desde'
				 }
			 }
		 },
		 hasta: {
			 validators: {
				 notEmpty: {
					 message: 'Selecione fecha hasta'
				 }
			 }
		 }
	 }
});