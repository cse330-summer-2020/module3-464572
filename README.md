# CSE330
464572

Evan Han - 464572

Possible Error: I had deleted the auto-generated repo by mistake, made a Piazza post. Unsure if that has any ramifications for grading.

Additionally, I did not implement a way to check if a URL points to an actual living website.

Creative Portion:
I implemented a like system by creating two tables: likes_stories and likes_comments. Additionally, I added a column
called 'likes' to my two original tables: stories and comments. A user can only like a story/post once because the PHP
queries in the likes tables whether or not there already exists a like entry specific to that story/comment. If it already exists,
the only form available to the user is a button that unlikes that specific story/comment. Whenever a like entry is added/removed,
the corresponding value in the stories/comments 'likes' column is incremented/decremented. Something that caught me off guard was
implementing story/comment deletion with the addition of likes. Say I wanted to delete a story. I couldn't just directly delete the story
because it is being used as a foreign key elsewhere in comments and/or likes. I needed to first delete all story likes, followed by comment likes, then comments, and lastly the story itself. One can also view all stories/comments they have liked by clicking on their username in the top right.



Link: http://ec2-100-25-133-18.compute-1.amazonaws.com/~evanhan6561/module3/login.html

Some Pre-made Users:
 - Username: test1
 - Password: password

 - Username: test2
 - Password: password2