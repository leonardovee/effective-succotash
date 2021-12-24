import { DepositModel } from '@/domain/model/deposit'
import { WithdrawModel } from '@/domain/model/withdraw'

export interface CreateTransaction {
  create: (deposit: DepositModel, withdraw: WithdrawModel) => Promise<TransactionModel>
}