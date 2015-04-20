reiZ
====

What is reiZ?
reiZ is yet another CMS written in php (php5.3+), which aims at taking advantage of newer functionality of php, rather than being backward compatible.

What are the goals of reiZ?
I have a strong focus on creating a system that can easily provide a clean html output that is properly tabbed and validatable, or alternatively output in a single line for minimal bandwidth usage. This is accomplished by storing all markup in (rearrangeble) objects and only "rendering" the output once the request has been completed. This can potentially consume more RAM, but also has the added benefit of allowing redirection up until the point of rendering (since there are no heders sent). Coupled with OutputBuffering, it might also be possible at some point to catch, scrape and re-render native php errors.

Why make reiZ? There are already so many CMSs...
Well, I have tried quite a few of them throughout the years, but I haven't found one that works the way I want it to. I was a fan of a specific one in .net, but I don't want a system that requires an IIS. I'm not creating this system in the hope that it will become the defacto standard of CMSs worldwide, even if I'm the only one who is going to use it, just having a system that works like I want it to is going to be a dream come trough.

Why that name?
The name may or may not be subject to change, and has in fact already had a couple of other ones... What it means? Well, who knows... It might be a lazy spelling of "racey", or a racey mis-spelling of "lazy"... Some say that it is merely the name of the "rays" that shine out between a fine set of queer buttocks... Or something with "race", maybe? I dunno, your guess is as good as mine, you just make it mean whatever you want it to...
