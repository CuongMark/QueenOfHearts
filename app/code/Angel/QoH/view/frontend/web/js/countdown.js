define([], function () {
    getTimeFormated = function(time, format) {
        if (typeof format == 'undefined'){
            format = '<span class="countdown_row countdown_show4"><span class="countdown_section"><span class="countdown_amount">%d</span><br>Days</span><span class="countdown_section"><span class="countdown_amount">%h</span><br>Hours</span><span class="countdown_section"><span class="countdown_amount">%m</span><br>Minutes</span><span class="countdown_section"><span class="countdown_amount">%s</span><br>Seconds</span></span>';
        }
        if (time<=0){
            return 'Finished';
        }
        var days = Math.floor(time/86400);
        var hours = Math.floor((time - 86400*days)/3600);
        var minutes = Math.floor((time - 86400*days - 3600*hours)/60);
        var seconds = time%60;
        if(format) {
            return format.replace(/%d|%h|%m|%s/gi, function (x) {
                switch (x) {
                    case '%d':
                        return days?days:'0';
                    case '%h':
                        return hours?hours:'0';
                    case '%m':
                        return minutes?minutes:'0';
                    case '%s':
                        return seconds?seconds:'0';

                }
            });
        }
        return '';
    };

    return function(config, node)
    {
        // retrieve the value from the span
        var sec = config.time_left;
        var endTime = parseInt((new Date().getTime())/1000) + sec;
        var timer = setInterval(function() {
            var now = parseInt((new Date().getTime())/1000);
            node.innerHTML = getTimeFormated(endTime-now);
            if (endTime-now <= 0) {
                clearInterval(timer);
                window.location.reload();
            }
        }, 1000);
    };
});