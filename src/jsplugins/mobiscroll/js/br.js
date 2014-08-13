(function ($) {
    $.scroller.i18n.br = $.extend($.scroller.i18n.br, {
        cancelText:'Cancelar',
        setText: 'Selecionar'
    });
})(jQuery);

(function ($) {
    $.scroller.i18n.br = $.extend($.scroller.i18n.br || {}, {
        cancelText:'Cancelar',
        setText: 'Selecionar',
        dateFormat: 'dd/mm/yy',
        timeFormat: 'HH:ii',
        dateOrder: 'D dd MMyy',

        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado'],
        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        yearText: 'Ano',
        monthText:	'Mês',
        dayText: 'Dia',            
        hourText: 'Hora',
        minuteText: 'Minuto',
        secText:	'Segundos',
        nowText:	'Agora',
        timeWheels: 'HH:ii',
        stepMinute: 10,
        endYear: '2100'     
    });
})(jQuery);