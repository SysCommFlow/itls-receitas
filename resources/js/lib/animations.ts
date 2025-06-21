import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

export const fadeInUp = (element: string | Element, delay = 0) => {
  gsap.fromTo(element,
    { y: 30, opacity: 0 },
    { y: 0, opacity: 1, duration: 0.6, delay, ease: "power2.out" }
  )
}

export const scaleIn = (element: string | Element, delay = 0) => {
  gsap.fromTo(element,
    { scale: 0.8, opacity: 0 },
    { scale: 1, opacity: 1, duration: 0.5, delay, ease: "back.out(1.7)" }
  )
}

export const slideInRight = (element: string | Element, delay = 0) => {
  gsap.fromTo(element,
    { x: 50, opacity: 0 },
    { x: 0, opacity: 1, duration: 0.6, delay, ease: "power2.out" }
  )
}

export { gsap, ScrollTrigger }
