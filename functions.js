//	Функция запуска оработки данных и их последующая запись в БД
//	Запускается в ручную через консоль из панели разработчика в браузере, при необходимости её можно прикрепить к кнопке на странице сайта
function upload_data()
{
	if(typeof upload_ajax !== 'undefined')
	{
		upload_ajax.abort();
	}
	
	upload_ajax = jQuery.ajax({
		url         : 'import.php',
		type        : 'POST',
		data        : {},
		beforeSend  : function()
		{
			
		},
		success     : function(result)
		{
			console.log(result);
		},
		error       : function(a)
		{
			console.log(a.responseText);
		},
		dataType    : 'text',
		response    : 'text'
	});
}

//	Функция запуска создания файлов и их последующее скачивание
function export_to_file(e)
{
	if(typeof export_ajax !== 'undefined')
	{
		export_ajax.abort();
	}
	
	file_name = e.data.file;
	if(file_name == 'type_a')
	{//	Файл с полным списком
		export_ajax = jQuery.ajax({
			url         : 'ajax.php',
			type        : 'POST',
			data        : {
				ajax: 'export_to_file',
				file_name: file_name
			},
			beforeSend  : function()
			{
				
			},
			success     : function(result)
			{
				var link = document.createElement('a');
				link.setAttribute('href', result);
				link.setAttribute('download', '');
				link.click();
			},
			error       : function(a)
			{
				console.log(a.responseText);
			},
			dataType    : 'text',
			response    : 'text'
		});
	}
	else if(file_name == 'type_b')
	{//	Файл с первым уровнем вложености
		export_ajax = jQuery.ajax({
			url         : 'ajax.php',
			type        : 'POST',
			data        : {
				ajax: 'export_to_file',
				file_name: file_name
			},
			beforeSend  : function()
			{
				
			},
			success     : function(result)
			{
				var link = document.createElement('a');
				link.setAttribute('href', result);
				link.setAttribute('download', '');
				link.click();
			},
			error       : function(a)
			{
				console.log(a.responseText);
			},
			dataType    : 'text',
			response    : 'text'
		});
	}
}