import CardModel from "./card.model";

export default interface RosterModel {
  id?: string;
  customName: string;
  cards: CardModel[];
}
