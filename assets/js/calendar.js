class Calendar{
    constructor({
        element,
        defaultDate
    }){
        if (element instanceof HTMLElement) {
            this.element = element;
        }else{
            throw new Error("element should be HTMLElement");
        }

        if (defaultDate instanceof Date) {
            this.defaultDate = defaultDate;
        }else{
            this.defaultDate = new Date();
        }

        this.#init();
    }

    //private properties
    #year;
    #month; //month start from 1
    #date;
    #dateString;
    //private methods
    #init = () => {
        const defaultYear = this.defaultDate.getFullYear();
        //month start from 1
        const defaultMonth = this.defaultDate.getMonth() + 1;
        const defaultDate = this.defaultDate.getDate();
        this.#setDate(defaultYear, defaultMonth, defaultDate);
        this.#listenEvents();
    }

    #listenEvents = () => {
        //DOMs
        const lastYearButton = this.element.querySelector('.lastYear');
        const lastMonthButton = this.element.querySelector('.lastMonth');
        const nextMonthButton = this.element.querySelector('.nextMonth');
        const nextYearButton = this.element.querySelector('.nextYear');

        //click last year
        lastYearButton.addEventListener('click', () => {
            this.#year--;
            this.#setDate(this.#year, this.#month);
        });
        //click next year
        nextYearButton.addEventListener('click', () => {
            this.#year++;
            this.#setDate(this.#year, this.#month);
        });
        //click last month
        lastMonthButton.addEventListener('click', () => {
            if (this.#month === 1) {
                this.#month = 12;
                this.#year--;
            }else{
                this.#month--;
            }
            this.#setDate(this.#year, this.#month);
        });
        //click next month
        nextMonthButton.addEventListener('click', () => {
            if (this.#month === 12) {
                this.#month = 1;
                this.#year++;
            }else{
                this.#month++;
            }
            this.#setDate(this.#year, this.#month);
        });
        //click dates
        //con esta función se puede obtener la fecha del dom para enviarla para programar.
        this.element.addEventListener('click', (e) => {
            if (e.target.classList.contains('date')) {
                console.log(e.target.title);
                const params = e.target.title.split('-').map(str => parseInt(str, 10));
                this.#setDate(...params);
            }
        });
    }

    #setDate = (year, month, date) => {
        this.#year = year;
        this.#month = month;
        this.#date = date;
        //the only place to do renders
        this.#renderCurrentDate();
        this.#renderDates();
    }

    #renderCurrentDate = () => {
        const currentDateEL = this.element.querySelector('.currentDate');
        this.#dateString = this.#getDateString(this.#year, this.#month, this.#date);
        currentDateEL.textContent = this.#dateString;
    }

    #getDateString = (year, month, date) => {
        if (date) {
            return `${year}-${month}-${date}`
        }else{
            return `${year}-${month}`
        }
    }

    #renderDates = () =>{
        const datesEL = document.querySelector('.dates');
        //clear before render
        datesEL.innerHTML = '';

        const dayCountInCurrentMonth = this.#getDayCount(this.#year, this.#month);
        console.log('dayCountInCurrentMonth',dayCountInCurrentMonth);
        const firstDayInCurrentMonth = this.#getFirstDay();
        console.log('firstDayInCurrentMonth',firstDayInCurrentMonth);

        const { lastMonth, yearOfLastMonth, dayCountOfLastMonth} = this.#getLastMonthInfo();
        const { nextMonth, yearOfNextMonth } = this.#getNextMonthInfo();

        for (let i = 1; i <= 42; i++) {
            const dateEL = document.createElement('button');
            dateEL.classList.add('date');
            let date;
            let dateString;
            if (firstDayInCurrentMonth > 1 && i < firstDayInCurrentMonth) {
                //show dates in last month
                date = dayCountOfLastMonth - (firstDayInCurrentMonth - i) + 1;
                dateString = this.#getDateString(yearOfLastMonth, lastMonth, date);
            }else if(i >= dayCountInCurrentMonth + firstDayInCurrentMonth){
                //show dates in next month
                date = i - (dayCountInCurrentMonth + firstDayInCurrentMonth) + 1;
                dateString = this.#getDateString(yearOfNextMonth, nextMonth, date);
            }else{
                //show dates in current month
                date = i - firstDayInCurrentMonth + 1;
                dateString = this.#getDateString(this.#year, this.#month, date);
                dateEL.classList.add('currentMonth');
                if (date === this.#date) {
                    dateEL.classList.add('selected');
                }
            }

            dateEL.textContent = date;
            dateEL.title = dateString;
            datesEL.append(dateEL);
        }
    }

    #getLastMonthInfo = () => {
        let lastMonth = this.#month - 1;
        let yearOfLastMonth = this.#year;
        if (lastMonth === 0) {
            lastMonth = 12;
            yearOfLastMonth -= 1;
        }
        const dayCountOfLastMonth = this.#getDayCount(yearOfLastMonth, lastMonth);
        return {
            lastMonth,
            yearOfLastMonth,
            dayCountOfLastMonth
        }
    }

    #getNextMonthInfo = () => {
        let nextMonth = this.#month + 1;
        let yearOfNextMonth = this.#year;
        if (nextMonth === 13) {
            nextMonth = 1;
            yearOfNextMonth += 1;
        }
        let dayCountInNextMonth = this.#getDayCount(yearOfNextMonth, nextMonth);
        return {
            nextMonth,
            yearOfNextMonth,
            dayCountInNextMonth
        }
    }
/**
 * get day count in a specific month by returning the last date of the month
 * @param {number} year year number
 * @param {number} month month number that starts
 * @returns 
 */
    #getDayCount = (year, month) => {
        return new Date(year, month, 0).getDate();
    }

    #getFirstDay = () => {
        let day = new Date(this.#year, this.#month, -1, 1).getDay();
        //day of sunday is 0, use 7 for calculation
        return day === 0 ? 7 : day;
    }
}