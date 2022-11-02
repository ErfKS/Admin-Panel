class Scroll_manager{
    static saveScroll = ()=>{
        //get current scroll
        let current_scroll_y=$(window).scrollTop();
        let current_scroll_x=$(window).scrollLeft();

        //save current scroll
        localStorage.setItem('scroll_y',current_scroll_y);
        localStorage.setItem('scroll_x',current_scroll_x);
    }

    static getScroll = ()=>{
        //get saved scroll
        let scroll_y=localStorage.getItem('scroll_y');
        let scroll_x=localStorage.getItem('scroll_x');

        //check exist and set scroll
        if(scroll_y && scroll_x) {
            //set scroll from localStorage
            $(window).scrollTop(scroll_y);
            $(window).scrollLeft(scroll_x);

            //remove saved scroll
            localStorage.removeItem('scroll_y');
            localStorage.removeItem('scroll_x');

            return true;
        }

        //if scroll is not exist
        return false;
    }
}
