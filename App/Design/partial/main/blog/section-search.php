<div class="widget">
    <div class="search_form">
        <form action="<?= url('home.blog'); ?>" method="get" id="__form_blog_search">
            <input class="form-control" placeholder="جستجو..." type="text" name="inp-blog-search-side"
                   value="<?= input()->get('q', ''); ?>">
            <button type="submit" title="Subscribe" class="btn icon_search"
                    name="submit" value="Submit">
                <i class="ion-ios-search-strong"></i>
            </button>
        </form>
    </div>
</div>