(function() {

window.App = {
	Models: {},
	Collections: {},
	Views: {}
};

window.template = function(id) {
	return _.template( $('#' + id).html() );
};


// Article Model
App.Models.Article = Backbone.Model.extend({
	defaults: {
            id: 0,
            title: 'Guest User',
            content: 'conTenee'	
	},
    url: "http://localhost/zend1xxx/public/index.php/article/sync"
});

// A List of People
App.Collections.Article = Backbone.Collection.extend({
        url: 'http://localhost/zend1xxx/public/index.php/article/view',
	model: App.Models.Article,
        parse: function (response) {
			
            console.log("Inside Parse");
            console.log('reposne',response);

            //Parse the response and construct models			
            for (var i = 0, length = response.length; i < length; i++) {



                    //push the model object
                    this.push(response[i]);	
            }

            console.log(this.toJSON());

            //return models
            return this.models;
        },
        initialize: function () {
            this.bind("reset", function (model, options) {
                console.log("Inside event");
                console.log(model);

            });
        }
});


// View for all Article
App.Views.Article = Backbone.View.extend({
	tagName: 'ul',
	initialize: function() {
		this.collection.on('add', this.addOne, this);
	},
	render: function() {
		this.collection.each(this.addOne, this);

		return this;
	},

	addOne: function(article) {
		var articleView = new App.Views.Articles({ model: article });
		this.$el.append(articleView.render().el);
	}
});

// The View for a Article
App.Views.Articles = Backbone.View.extend({
	tagName: 'li',

	template: template('articleTemplate'),	
	
	initialize: function(){
		this.model.on('change', this.render, this);
		this.model.on('destroy', this.remove, this);
	},
	
	events: {
	 'click .edit' : 'editArticle',
	 'click .delete' : 'DestroyArticle'	
	},
	
	editArticle: function(){
            var newName = prompt("Please enter the new title", this.model.get('title'));
            if (!newName) return;
            this.model.set('title', newName);
            Backbone.sync('update',this.model);
	},
	
	DestroyArticle: function(){
            this.model.destroy();
            var id = this.model.get('id');
             Backbone.sync('delete',this.model);
      
	},
	
	remove: function(){
		this.$el.remove();
	},
	
	render: function() {
		this.$el.html( this.template(this.model.toJSON()) );
		return this;
	}
});


App.Views.AddArticle = Backbone.View.extend({
    el: '#addArticle',

    events: {
        'submit': 'submit'
    },

    submit: function(e) {
        e.preventDefault();
        var newTitle = $(e.currentTarget).find('input[type=text]').val();
        var newContent = $(e.currentTarget).find('textarea[name=content]').val();
        var article = new App.Models.Article({ title: newTitle,content:newContent });
        this.collection.add(article);
        Backbone.sync("create", article);
    }
});

/* Run Start here */
var ArticleCollection = new App.Collections.Article();
ArticleCollection.fetch({
        success: function(response,xhr) {
            var addArticleView = new App.Views.AddArticle({ collection: ArticleCollection });

            ArticleView = new App.Views.Article({ collection: ArticleCollection });

            $("#article-listing-holder").html(ArticleView.render().el);

        },
        error: function (errorResponse) {
            console.log(errorResponse)
        }
    });
})();
