import { DepositModel } from '@/domain/model/deposit'
import { WithdrawModel } from '@/domain/model/withdraw'
import { TransactionModel } from '@/domain/model/transaction'

export interface CreateTransactionByDepositAndWithdrawRepository {
  createByDepositAndWithdraw: (deposit: DepositModel, withdraw: WithdrawModel) => Promise<TransactionModel>
}
