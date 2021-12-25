import { DepositModel } from './deposit'
import { WithdrawModel } from './withdraw'

export interface TransactionModel {
  id?: string
  deposit: DepositModel
  withdraw: WithdrawModel
}
