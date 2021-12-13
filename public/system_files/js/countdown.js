function getTimeRemaining(endtime) {
  let time = Date.parse(endtime) - Date.parse(new Date());
  time = new Date(time);
  // let mn = 30.416666666666;
  // var minutes = Math.floor((t / 1000 / 60) % 60);
  // var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  // var days = Math.floor(t / (1000 * 60 * 60 * 24) % mn);
  // var months = Math.floor(t / (1000 * 60 * 60 * 24 * mn) % 12);
  // var years = Math.floor(t / (1000 * 60 * 60 * 24 * mn * 12));

  let tend = moment(endtime);
  let t = moment();
  let temp = t;
  
  let years = 0;
  let months = Math.abs(tend.diff(t, 'months'));
  while(months > 12){
    years++;
    months = months % 12;
  }
  
  temp.add(years, 'years');
  temp.add(months, 'months');

  let days = Math.abs(temp.diff(tend, 'days'));
  temp.add(days, 'days');
  let hours = Math.abs(temp.diff(tend, 'hours'));
  temp.add(hours, 'hours');
  let minutes = Math.abs(temp.diff(tend, 'minutes'));

  return {
    'total': time,
    'years': years,
    'months': months,
    'days': days,
    'hours': hours,
    'minutes': minutes+1
  };
}

function initializeClock(id, endtime) {
  var clock = document.getElementById(id);
  var yearsSpan = clock.querySelector('.followUp-years');
  var monthsSpan = clock.querySelector('.followUp-months');
  var daysSpan = clock.querySelector('.followUp-days');
  var hoursSpan = clock.querySelector('.followUp-hours');
  var minutesSpan = clock.querySelector('.followUp-minutes');

  function updateClock() {
    var t = getTimeRemaining(endtime);
    if(t.years > 0){
      yearsSpan.innerHTML = (t.years)+'<div class="smalltext"> Y</div>';
      clock.querySelector('.followUp-years').classList.remove('d-none');
    }
    if(t.months > 0){
      monthsSpan.innerHTML = ('0' + t.months).slice(-2)+'<div class="smalltext"> M</div>';
      clock.querySelector('.followUp-months').classList.remove('d-none');
    }
    daysSpan.innerHTML = ('0' + t.days).slice(-2)+'<div class="smalltext"> D</div>';
    hoursSpan.innerHTML = ('0' + t.hours).slice(-2)+'<div class="smalltext"> H</div>';
    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2)+'<div class="smalltext"> m</div>';

    if (t.total <= 0) {
      clearInterval(timeinterval);
      $('#'+id).addClass('d-none');
      setfollowUp();
    }else{
      $('#'+id).removeClass('d-none');
    }
  }

  updateClock();
  var timeinterval = setInterval(updateClock, 60000);
}