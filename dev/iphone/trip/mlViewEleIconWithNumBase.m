//
//  mlViewEleIconWithNumBase.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-21.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "mlViewEleIconWithNumBase.h"

@implementation mlViewEleIconWithNumBase

@synthesize type;

@synthesize number;
@synthesize numberLb;
@synthesize dataPool;

- (id)initWithFrame:(CGRect)frame
{
    CGRect f = CGRectMake(frame.origin.x, frame.origin.y, 30, 30);
    self = [super initWithFrame:f];
    if (self) {
        
        [self setImage:self.image forState:UIControlStateNormal];
        [self setImage:self.imageApplication forState:UIControlStateApplication];
        self.backgroundColor = [UIColor clearColor];
        
        [self addTarget:self.delegate action:@selector(mlViewEleIconTouchUpInside:) forControlEvents:UIControlEventTouchUpInside];
        
        self.numberBg = [[UIView alloc] initWithFrame:CGRectMake(20,0, 15, 15)];
        self.numberBg.backgroundColor = [UIColor orangeColor];
        self.numberBg.layer.cornerRadius = 5;
        self.numberLb = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, 15, 15)];
        self.numberLb.text = [NSString stringWithFormat:@"%d" , self.number];
        self.numberLb.backgroundColor = [UIColor clearColor];
        self.numberLb.font = [UIFont systemFontOfSize:10];
        self.numberLb.textAlignment = UITextAlignmentCenter;

        [self.numberBg addSubview:self.numberLb];
        self.numberBg.hidden = YES;
        [self addSubview:self.numberBg];
        
        
    }
    return self;
}

- (void)setNumber:(int)n
{
    number = n;
    self.numberLb.text = [NSString stringWithFormat:@"%d" , self.number];
    if (self.number == 0) {
        self.numberBg.hidden = YES;
    } else {
        self.numberBg.hidden = NO;
    }
}

- (void)playSucessAni
{
    self.backgroundColor = [UIColor blueColor];
}

@end
