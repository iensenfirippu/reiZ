reiZ
====

What is reiZ?
reiZ is yet another CMS written in php (php5.3+). What sets it apart from others of it's kind, is its rather unorthodox focus on delivering proper, validatable, well-formed, clean, readable HTML. It accomplishes this by storing all the markup in nested php objects, which are "rendered" into HTML and sent upon the completion of the request. This will most likely consume a lot more RAM than using the rather standard "echo as you go" strategy, which most CMSs seems to do. Because of this, reiZ probably won't be powering multi-million user websites, but with proper caching it should still be more than servicable for small websites, or as intranet pages.

What are the benefits of doing this?
There are a few benefits to doing it this way, as well as caviats of course. Firstly, HTML tags and even complex HTML constructs can be prepared as large objects and included anywhere on a page, any number of times. It is also possible to edit any of the objects (or even rearrange them) up until the point of rendering. And since there are no headers sent out until then, it also allows for redirection up until that point. Of course, even though it is possible to edit, rearrange and redirect at any time, reliance upon this will cause additional response time, so it's more "in-case-of-emergency" than anything else. Another significant benefit is that the HTML can just as easily be rendered as "one-line", reducing line breaks and tabs to the bare minimum, reducing the overall transmission cost by up to about a third.

Why make reiZ? There are already so many CMSs...
Well, I have tried quite a few of them throughout the years, but I haven't found one that works the way I want it to. I was a fan of a particular one written in .net, but I don't want a system that requires an IIS, or indeed any Microsoft technology. I'm not creating this system in the hopes that it will become the next defacto standard of CMSs worldwide, even if I'm the only one in the world who is going to use it, just having a system that works like I want it to, is going to be a dream come trough.

What does the name mean?
The name may or may not be subject to change, and has in fact been changed a couple of times already. But the previous names were all punny and/or downright lame. I thought of just calling it "lazy", as in "I'm too lazy to come up with a proper name right now". But then I starting thinking in Japanese, and then I decided to just spruce up the word a bit. So yeah, the meaning is "Lazy", even though I actually pronounce it "Rays". But by all means, attribute it your own meaning if you want.
