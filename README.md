<div id="top"></div>     

# Tonality
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
        <li><a href="#creating-an-account">Creating an Account</a></li>
        <li><a href="#how-does-tonality-work">How does Tonality work?</a></li>
        <li><a href="#adding-to-your-profile">Adding to your Profile</a></li>
        <li><a href="#following-your-friends">Following your Friends</a></li>
        <li><a href="#vote-while-you-listen">Vote while you listen</a></li>
        <li><a href="#notifications">Notifications</a></li>
      </ul>
    </li>
    <li><a href="#videos">Videos</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>

<!-- ABOUT THE PROJECT -->
## About The Project  
Easily my largest project, Tonality is a fully developed social website. Tonality has a wide range of features, all centering around allowing users to share their musical interests, find new music, and share their opinions. I developed Tonality completely by myself, with feedback from two of my close friends, one of which had the original idea for the site. This website took over 600 hours to complete, and still has a lot of bugs that could be fixed. Considering it was developed soley by myself, I would like to think it isn't too bad.   
Please disregard the site speed, as it is currently on free hosting and has significantly decrease in overall speed.

### Built With
* [Spotify API](https://developer.spotify.com/documentation/web-api/)
* [Core PHP](https://www.php.net/)
* [mySQL](https://www.mysql.com/)
* [jQuery](https://jquery.com/)
* [Bootstrap](https://getbootstrap.com/)

### Creating an Account
To start using Tonality, a user just needs to visit [gotonality.com](http://gotonality.com) and navigate to the [signup page](http://gotonality.com/signup). Creating account is very straightforward: You'll be asked for your name, preferred username, email, password, and profile image. Tonality also has a 'remember me' feature, which will save your encrypted login information to a cookie and automatically login you in next time you visit the page. Once you create your account, you are ready to begin using Tonality!    
If a user ever wants to change any of their information, they can visit their profile and update their name, email, usernanme, or passowrd at any time.

### How does Tonality work?
At its core, Tonality aims to be a social network for music lovers. Everytime you visit the website, you are met with a main feed of music recommendations, specifically tailored to you using the Spotify API. While the API is used, a user does not need a Spotify account to use the site. Rather, their previously rated music on Tonality is used to get new recommendations. At the top of the page, a user can find the menu bar, which allows the to navigate through the sites main functions:
* **Home:** The [Home page](http://gotonality.com/home) of Tonality is the landing page. Here, the user can find our main function, the infinite recommendation feed. A user can scroll through this page and listen to new music using the Spotify Widget, rate the music, and continue to scroll as long as their heart desires. Above the feed, there is a slide set of newly released music, which uses the Spotify API to update itself as new music comes. Finally, the user can switch to an alternative following feed, which will show them the music that their friends have been rating, commenting on, and pinning.
* **Discover:** 
  * Explore: The [Explore page](http://gotonality.com/explore), similar to other social sites like Instagram, is a page with popular and trending music. This music is in a grid format, showing the user the album cover and opening a modal with the rating buttons and spotify widget upon being clicked. At the top of the page, a user can find Tonality's Top Charts, a variety of charts which ranks the music that has been rated on the site, such as Top 100, Bottom 100, Trending, and Gas of the Month.
  * Discover Quiz: The [discover quiz](http://gotonality.com/discover) is a 3 stage quiz that allows users to get even better tailored recommendations, and export them directly to a Spotify playlist. The quiz starts by asking for genres the user is interested in, then suggests some artists and allows the user to make selection, and finally offers a handful of songs to choose from. With all of that data, the quiz calls on the Spotify API to return a playlist of 50 specially recommended songs.
  * Genres: Next under the discover senction is the [Genres search](http://gotonality.com/genres). This page allows a user to select a genre they are interested in a shows them only songs from that genre.
  * Find Users: Lastly under the Discover tab is the [Find Users](http://gotonality.com/search-users) page. Tonality wouldn't be considered a social site without a these core pages. The Find Users tab allows users to search through all users on Tonality, view their profile, and follow them to begin getting their rated music, comments, and pins on their feed.
* **Discuss:**
  * Forums: A key part of Tonality is the [Community Forums](http://gotonality.com/community). The forums allow any user to create a thread under one of the preset categories, and discuss their ideas and opinion with other users. 
  * News: The [News page](http://gotonality.com/news) is home to any news Tonality has to release. So far not much, but maybe someday!
* **Profile:** Clicking on your user profile icon will allow you to head to your profile page. On your page, you can see what other users will see when they check you out. This page will include your profile stats, such as your number of followers and following, your total ratings, and your total reviews. This page will also have a slider featuring all of your pinned content. Finally, using the side bar on the left, you can view your upvoted (gassed) songs, downvoted (trashed) songs, and reviews. This page will allow you to scroll down all the way through your ratings and reviews, no matter how long! Users can also use this page to navigate to update their profile and view their notifications. 
* **Search:** By clicking the far right icon, a user can search any term they can imagine and will be met with results from artists, songs, albums, or even Tonality users!

### Adding to your Profile
By interacting with content on the site, a user can add to their personal profile. These interaction will help Tonality get a sense of your music taste and allow other users to see what you're interested in.
* **Rating:** On any page with songs listed, users will have the option to rate the song. Your vote will decide where the song ends up on your profile, either the gassed songs or the trashed songs. Gassing songs will also tell Tonality that is the kind of music you want recommended.
* **Reviews:** By clicking on a song, you will be able to see more information about it. This will include user reviews of the song. You can add a review by clikcing on the section that says 'Leave a review'. Your review will be under the song and users who visit will be able to like your review. The review will also be posted on your profile and shown on you followers feed.
* **Pins:** If you really like a song, you have the option to pin it, putting it at the top of your profile page. Users only have 12 pins, so they must be used wisely. A pin can be either a song, album, or artist. When a user adds a new pin, it will be shown in their followers feed.

### Following your Friends
If your friends are on Tonality, you can follow them by visiting their profile and pressing the Follow button. Once followed, that users content will begin to show up on you following feed.

### Vote while you listen
An added feature to Tonality is the Vote while you Listen widget. In the bottom right hand side of the website, users can pop-up the widget and sign into Spotify. As the user listens to music on Spotify, the song they are listening to will show up on the widget with the rating, and the arrows for the users to give their rating. For Spotify Premium users, their are also pause/play and skip/previous buttons.

### Notifications
On a users profile page, they can visit their notifications page. This page shows recent activity regarding your account. This could be new followers, likes on your reviews, or discussion on a forum thread you are a part of.

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- EXAMPLES -->
## Videos

https://user-images.githubusercontent.com/53005525/138234704-145ac295-43cc-41e3-98c9-1a8fc3ed7678.mp4

https://user-images.githubusercontent.com/53005525/138234713-03fb1099-c009-43e9-873b-4668315cfc56.mp4

https://user-images.githubusercontent.com/53005525/138234722-519abb5a-7fe4-4e5e-8dc0-c615122fefe4.mp4


<!-- CONTACT -->
## Contact

Eric Love - [LinkedIn](https://www.linkedin.com/in/ericlove02) - [eric.love02@yahoo.com](mailto:eric.love02@yahoo.com)

Project Link: [https://github.com/ericlove02/Tonality](https://github.com/ericlove02/Tonality)

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[forks-shield]: https://img.shields.io/github/forks/ericlove02/Tonality.svg?style=for-the-badge
[forks-url]: https://github.com/ericlove02/Tonality/network/members
[stars-shield]: https://img.shields.io/github/stars/ericlove02/Tonality.svg?style=for-the-badge
[stars-url]: https://github.com/ericlove02/Tonality/stargazers
[issues-shield]: https://img.shields.io/github/issues/ericlove02/Tonality.svg?style=for-the-badge
[issues-url]: https://github.com/ericlove02/Tonality/issues
