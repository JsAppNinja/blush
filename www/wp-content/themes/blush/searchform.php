<form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
    <div class="input-group">
      <input type="text" class="form-control" value="<?php if (trim(wp_specialchars($s, 1)) != '') echo trim(wp_specialchars($s, 1)); else echo ' '; ?>" name="s" id="s"/>
      <span class="input-group-btn">
        <button class="btn btn-primary" type="submit">Search</button>
      </span>
    </div>
</form>