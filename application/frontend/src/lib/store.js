// src/lib/store.js
import { writable } from 'svelte/store';

export const currentTab = writable('dashboard');