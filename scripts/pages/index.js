import setGlobalEvents from "../core/events.js"
import { getMe } from "../services/user.js";
setGlobalEvents();
console.log(getMe());