Redactor.add("plugin", "grid", {
    translations: {
        en: {
            'grid': "BSGrid",
            "grid-row": "Row",
            "grid-col": "col-md-6",
        }
    },
    init: function(t) {
        this.app = t, this.opts = t.opts, this.lang = t.lang, this.block = t.block, this.toolbar = t.toolbar
    },
    start: function() {
        var t = {};
        t.left = {
            title: this.lang.get("align-left"),
            api: "plugin.alignment.set",
            args: "left"
        }, t.center = {
            title: this.lang.get("align-center"),
            api: "plugin.alignment.set",
            args: "center"
        }, t.right = {
            title: this.lang.get("align-right"),
            api: "plugin.alignment.set",
            args: "right"
        }, t.justify = {
            title: this.lang.get("align-justify"),
            api: "plugin.alignment.set",
            args: "justify"
        };
        var i = this.toolbar.addButton("alignment", {
            title: this.lang.get("align")
        });
        i.setIcon('<i class="re-icon-alignment"></i>'), i.setDropdown(t)
    },
    set: function(t) {
        if ("left" === t && "ltr" === this.opts.direction) return this._remove();
        var i = {
            style: {
                "text-align": t
            }
        };
        this.block.toggle(i)
    },
    _remove: function() {
        this.block.remove({
            style: "text-align"
        })
    }
});
/*(function($) {
	
			 $.Redactor.prototype.grid = function() {
				 	
					// Placeholder to make sure the DIVs don't disappear!
					var placeholder = '&#8203;&nbsp;'; 
				 
					 return {
							 init: function() {
									 	var dropdown = {};
										dropdown.grid0 = { title: '█████ █████', func: this.grid.grid0 };
										dropdown.grid1 = { title: '███████ ███', func: this.grid.grid1 };
										dropdown.grid2 = { title: '███ ███████', func: this.grid.grid2 };
										dropdown.grid3 = { title: '███ ███ ███', func: this.grid.grid3 };
										dropdown.grid4 = { title: '██ ██ ██ ██', func: this.grid.grid4 };
										dropdown.grid5 = { title: '█ █ █ █ █ █', func: this.grid.grid5 };
									 var button = this.button.addFirst('grid', 'Grid');
									 this.button.addDropdown(button, dropdown);
							 },
							 grid0: function(buttonName) {
									 this.insert.html('<div class=\"row\"><div class=\"col-sm-6\"><div>' + placeholder + '</div></div><div class=\"col-sm-6\"><div>' + placeholder + '</div></div></div>');
							 }, grid1: function(buttonName) {
									 this.insert.html('<div class=\"row\"><div class=\"col-sm-8 col-lg-9\"><div>' + placeholder + '</div></div><div class=\"col-sm-4 col-lg-3\"><div>' + placeholder + '</div></div></div>');
							 }, grid2: function(buttonName) {
									 this.insert.html('<div class=\"row\"><div class=\"col-sm-4 col-lg-3\"><div>' + placeholder + '</div></div><div class=\"col-sm-8 col-lg-9\"><div>' + placeholder + '</div></div></div>');
							 }, grid3: function(buttonName) {
									 this.insert.html('<div class=\"row\"><div class=\"col-sm-4\"><div>' + placeholder + '</div></div><div class=\"col-sm-4\"><div>' + placeholder + '</div></div><div class=\"col-sm-4\"><div>' + placeholder + '</div></div></div>');
							 }, grid4: function(buttonName) {
									 this.insert.html('<div class=\"row\"><div class=\"col-sm-3\"><div>' + placeholder + '</div></div><div class=\"col-sm-3\"><div>' + placeholder + '</div></div><div class=\"col-sm-3\"><div>' + placeholder + '</div></div><div class=\"col-sm-3\"><div>' + placeholder + '</div></div></div>');
							 }, grid5: function(buttonName) {
									 this.insert.html('<div class=\"row\"><div class=\"col-xs-6 col-sm-4 col-md-2\"><div>' + placeholder + '</div></div><div class=\"col-xs-6 col-sm-4 col-md-2\"><div>' + placeholder + '</div></div><div class=\"col-xs-6 col-sm-4 col-md-2\"><div>' + placeholder + '</div></div><div class=\"col-xs-6 col-sm-4 col-md-2\"><div>' + placeholder + '</div></div><div class=\"col-xs-6 col-sm-4 col-md-2\"><div>' + placeholder + '</div></div><div class=\"col-xs-6 col-sm-4 col-md-2\"><div>' + placeholder + '</div></div></div>');
							 }
					 };
			 
			 };
			 
	 })(jQuery);
*/