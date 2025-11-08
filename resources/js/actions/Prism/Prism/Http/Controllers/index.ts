import PrismChatController from './PrismChatController'
import PrismModelController from './PrismModelController'

const Controllers = {
    PrismChatController: Object.assign(PrismChatController, PrismChatController),
    PrismModelController: Object.assign(PrismModelController, PrismModelController),
}

export default Controllers