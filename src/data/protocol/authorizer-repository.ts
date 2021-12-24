import { DepositModel } from '@/domain/model/deposit'
import { WithdrawModel } from '@/domain/model/withdraw'

export interface AuthorizerRepository {
  authorize: (deposit: DepositModel, withdraw: WithdrawModel) => Promise<boolean>
}
