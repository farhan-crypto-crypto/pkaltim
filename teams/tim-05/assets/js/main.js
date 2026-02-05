// Register GSAP Plugins
gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {

    // Hero Animation
    const heroTl = gsap.timeline();
    heroTl.from(".hero-title", {
        y: 50,
        opacity: 0,
        duration: 1,
        ease: "power4.out",
        delay: 0.2
    })
        .from(".hero-subtitle", {
            y: 30,
            opacity: 0,
            duration: 1,
            ease: "power3.out"
        }, "-=0.6")
        .from(".hero-btn", {
            y: 20,
            opacity: 0,
            duration: 0.8,
            ease: "back.out(1.7)"
        }, "-=0.6");

    // Staggered Cards Animation
    gsap.utils.toArray('.card-destination').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: "top 90%",
            },
            y: 50,
            opacity: 0,
            duration: 0.8,
            delay: i * 0.1, // Stagger effect
            ease: "power3.out"
        });
    });

    // Feature Items Animation
    gsap.from(".feature-item", {
        scrollTrigger: {
            trigger: "#features",
            start: "top 80%",
        },
        y: 30,
        opacity: 0,
        duration: 0.8,
        stagger: 0.2,
        ease: "power2.out"
    });

});
