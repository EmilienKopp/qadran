import Sanctum from './Sanctum'
import Passport from './Passport'

const Laravel = {
    Sanctum: Object.assign(Sanctum, Sanctum),
    Passport: Object.assign(Passport, Passport),
}

export default Laravel