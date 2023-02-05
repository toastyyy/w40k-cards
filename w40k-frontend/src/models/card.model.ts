import AbilityModel from "./ability.model";
import MediumModel from "./medium.model";
import PsychicPowerModel from "./psychicPower.model";
import PsykerModel from "./psyker.model";
import UnitModel from "./unit.model";
import WeaponModel from "./weapon.model";

export default interface CardModel {
  id?: string;
  title: string;
  subtitle?: string;
  quote: string;
  points: number;
  abilities: AbilityModel[];
  weapons: WeaponModel[];
  psykers: PsykerModel[];
  psychicPowers: PsychicPowerModel[];
  units: UnitModel[];
  borderColor: string;
  textColor: string;

  backgroundImage: MediumModel;
  unitImage: MediumModel;
  boxBackground: MediumModel;
  factionLogo: MediumModel;
  keywords: string[];
  useAutomaticBackgroundRemoval: boolean;
  imageTranslateX: number;
  imageTranslateY: number;
  imageScale: number;
}
