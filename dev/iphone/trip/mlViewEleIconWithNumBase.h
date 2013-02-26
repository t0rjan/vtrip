//
//  mlViewEleIconWithNumBase.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-21.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <QuartzCore/QuartzCore.h>



@protocol mlViewEleIconDelegate <NSObject>

@optional
- (void)mlViewEleIconTouchUpInside:(id)sender;

@end

@interface mlViewEleIconWithNumBase : UIButton

@property (assign , nonatomic) NSString *type;
@property (assign , nonatomic) UIImage *image;
@property (assign , nonatomic) UIImage *imageApplication;
@property (assign , nonatomic) id<mlViewEleIconDelegate> delegate;
@property (assign , nonatomic) int number;
@property (assign , nonatomic) UIView *numberBg;
@property (retain , nonatomic) UILabel *numberLb;
@property (retain , nonatomic) NSMutableDictionary *dataPool;


- (void)playSucessAni;
- (void)updateNumber:(int)number;

@end


