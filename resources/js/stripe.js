
import { loadStripe } from '@stripe/stripe-js';

const stripePublicKey = document.querySelector('meta[name="stripe-public-key"]')?.content;

if (stripePublicKey) {
    const stripe = await loadStripe(stripePublicKey);
    window.stripe = stripe;
}